package com.jeric.ricafrente.naruto.ui.akatsuki.character

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
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

class AkatsukiCharacterViewModel(
    private val useCases: UseCases
) : ViewModel() {
    private val _uiState = MutableStateFlow(AkatsukiCharacterStateEvent.UiState())
    val uiState: StateFlow<AkatsukiCharacterStateEvent.UiState> get() = _uiState.asStateFlow()

    private val _navigation = Channel<AkatsukiCharacterStateEvent.Navigation>()
    val navigation: Flow<AkatsukiCharacterStateEvent.Navigation> = _navigation.receiveAsFlow()


    fun onEvent(event: AkatsukiCharacterStateEvent.Event){
        when(event){
            is AkatsukiCharacterStateEvent.Event.GotoDetailsEvent -> getDetails(event.id)
            AkatsukiCharacterStateEvent.Event.GotoBackEvent -> {
                viewModelScope.launch {
                    _navigation.send(AkatsukiCharacterStateEvent.Navigation.GotoSearchNav)
                }
            }
        }
    }

    private fun getDetails(id: Int) = viewModelScope.launch {
        _uiState.update { it.copy(isLoading = true) }
        val result = useCases.getSelectedAkatsukiUseCase(id = id)
        delay(500)
        _uiState.update { it.copy(isSuccess = result, isLoading = false) }
    }

}

object AkatsukiCharacterStateEvent {
    data class UiState(
        val isLoading: Boolean = false,
        val error: String ?=null,
        val isSuccess: AkatsukiModel?= null
    )
    sealed class Navigation {
        data object GotoSearchNav: Navigation()
    }
    sealed interface Event{
        data class GotoDetailsEvent(val id: Int): Event
        data object GotoBackEvent: Event
    }
}

