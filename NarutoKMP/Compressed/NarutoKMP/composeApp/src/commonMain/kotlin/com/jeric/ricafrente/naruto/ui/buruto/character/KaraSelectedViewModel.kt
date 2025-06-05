package com.jeric.ricafrente.naruto.ui.buruto.character

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
import kotlinx.coroutines.flow.receiveAsFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch

class KaraSelectedViewModel(
    private val useCases: UseCases
) : ViewModel() {

    private val _uiState = MutableStateFlow(KaraSelectedStateEvent.UiState())
    val uiState: StateFlow<KaraSelectedStateEvent.UiState> get() = _uiState.asStateFlow()

    private val _navigation = Channel<KaraSelectedStateEvent.Navigation>()
    val navigation: Flow<KaraSelectedStateEvent.Navigation> = _navigation.receiveAsFlow()


    fun onEvent(event: KaraSelectedStateEvent.Event){
        when(event){
            is KaraSelectedStateEvent.Event.GotoDetailsEvent -> getDetails(event.id)
            KaraSelectedStateEvent.Event.GotoBackEvent -> {
                viewModelScope.launch {
                    _navigation.send(KaraSelectedStateEvent.Navigation.GotoSearchNav)
                }
            }
        }
    }

    private fun getDetails(id: Int) = viewModelScope.launch {
        _uiState.update { it.copy(isLoading = true) }
        val result = useCases.getSelectedKaraUseCase(id = id)
        delay(500)
        _uiState.update { it.copy(isSuccess = result, isLoading = false) }
    }

}

object KaraSelectedStateEvent {
    data class UiState(
        val isLoading: Boolean = false,
        val error: String ?=null,
        val isSuccess: KaraModel?= null
    )
    sealed class Navigation {
        data object GotoSearchNav: Navigation()
    }
    sealed interface Event{
        data class GotoDetailsEvent(val id: Int): Event
        data object GotoBackEvent: Event
    }
}
