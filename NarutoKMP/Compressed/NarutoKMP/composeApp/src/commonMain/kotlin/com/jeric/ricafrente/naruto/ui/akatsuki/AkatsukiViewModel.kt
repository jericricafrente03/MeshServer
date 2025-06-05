package com.jeric.ricafrente.naruto.ui.akatsuki

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.model.buruto.KaraModel
import com.jeric.ricafrente.naruto.core.use_cases.UseCases
import kotlinx.coroutines.channels.Channel
import kotlinx.coroutines.delay
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.collectLatest
import kotlinx.coroutines.flow.receiveAsFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch

class AkatsukiViewModel(
    useCases: UseCases
) : ViewModel() {
    private val _uiState = MutableStateFlow(AkatsukiStateEvent.UiState())
    val uiState: StateFlow<AkatsukiStateEvent.UiState> get() = _uiState.asStateFlow()

    private val _navigation = Channel<AkatsukiStateEvent.Navigation>()
    val navigation: Flow<AkatsukiStateEvent.Navigation> = _navigation.receiveAsFlow()

    init {
        _uiState.update { it.copy(isLoading = true) }
        viewModelScope.launch {
            try {
                delay(1000)
                useCases.getAllAkatsukiUseCase().collectLatest { result ->
                    _uiState.update { it.copy(isSuccess = result.data ?: emptyList(), isLoading = false) }
                }
            }catch (e: Exception){
                e.printStackTrace()
                _uiState.update { it.copy(error = e.message) }
            }
        }
    }


    fun onEvent(event: AkatsukiStateEvent.Event){
        when(event){
            is AkatsukiStateEvent.Event.GotoDetailsEvent -> {
                viewModelScope.launch {
                    _navigation.send(AkatsukiStateEvent.Navigation.GotoDetailsNav(event.id))
                }
            }
            AkatsukiStateEvent.Event.GotoBackEvent -> {
                viewModelScope.launch {
                    _navigation.send(AkatsukiStateEvent.Navigation.GotoBackNav)
                }
            }
        }
    }

}

object AkatsukiStateEvent {
    data class UiState(
        val isLoading: Boolean = false,
        val error: String ?=null,
        val isSuccess: List<AkatsukiModel> = emptyList()
    )
    sealed class Navigation {
        data object GotoBackNav: Navigation()
        data class GotoDetailsNav(val id : String): Navigation()
    }
    sealed interface Event{
        data class GotoDetailsEvent(val id: String): Event
        data object GotoBackEvent: Event
    }
}
