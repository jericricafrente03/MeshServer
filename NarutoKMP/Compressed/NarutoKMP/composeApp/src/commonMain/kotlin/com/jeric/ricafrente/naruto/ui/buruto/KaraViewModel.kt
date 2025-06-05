package com.jeric.ricafrente.naruto.ui.buruto

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
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

class KaraViewModel(
    private val useCases: UseCases
) : ViewModel() {

    private val _uiState = MutableStateFlow(KaraViewStateEvent.UiState())
    val uiState: StateFlow<KaraViewStateEvent.UiState> get() = _uiState.asStateFlow()

    private val _navigation = Channel<KaraViewStateEvent.Navigation>()
    val navigation: Flow<KaraViewStateEvent.Navigation> = _navigation.receiveAsFlow()

    init {
        _uiState.update { it.copy(isLoading = true) }
        viewModelScope.launch {
            try {
                delay(1000)
                useCases.getAllKaraUseCase().collectLatest { result ->
                    _uiState.update { it.copy(isSuccess = result.data ?: emptyList(), isLoading = false) }
                }
            }catch (e: Exception){
                e.printStackTrace()
                _uiState.update { it.copy(error = e.message) }
            }
        }
    }


    fun onEvent(event: KaraViewStateEvent.Event){
        when(event){
            is KaraViewStateEvent.Event.GotoDetailsEvent -> {
                viewModelScope.launch {
                    _navigation.send(KaraViewStateEvent.Navigation.GotoDetailsNav(event.id))
                }
            }
            KaraViewStateEvent.Event.GotoBackEvent -> {
                viewModelScope.launch {
                    _navigation.send(KaraViewStateEvent.Navigation.GotoBackNav)
                }
            }
        }
    }

}

object KaraViewStateEvent {
    data class UiState(
        val isLoading: Boolean = false,
        val error: String ?=null,
        val isSuccess: List<KaraModel> = emptyList()
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
