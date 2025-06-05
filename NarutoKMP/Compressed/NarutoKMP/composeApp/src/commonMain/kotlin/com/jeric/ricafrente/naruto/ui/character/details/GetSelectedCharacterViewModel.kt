package com.jeric.ricafrente.naruto.ui.character.details

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
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

class GetSelectedCharacterViewModel(
    private val useCases: UseCases
) : ViewModel() {
    private val _uiState = MutableStateFlow(GetSelectedCharacterStateEvent.UiState())
    val uiState: StateFlow<GetSelectedCharacterStateEvent.UiState> get() = _uiState.asStateFlow()

    private val _navigation = Channel<GetSelectedCharacterStateEvent.Navigation>()
    val navigation: Flow<GetSelectedCharacterStateEvent.Navigation> = _navigation.receiveAsFlow()


    fun onEvent(event: GetSelectedCharacterStateEvent.Event){
        when(event){
            is GetSelectedCharacterStateEvent.Event.GotoDetailsEvent -> getDetails(event.id)
            GetSelectedCharacterStateEvent.Event.GotoBackEvent -> {
                viewModelScope.launch {
                    _navigation.send(GetSelectedCharacterStateEvent.Navigation.GotoSearchNav)
                }
            }
        }
    }

    private fun getDetails(id: Int) = viewModelScope.launch {
        _uiState.update { it.copy(isLoading = true) }
        val result = useCases.getSelectedHeroesUseCase(id = id)
        delay(500)
        _uiState.update { it.copy(isSuccess = result, isLoading = false) }
    }

}

object GetSelectedCharacterStateEvent {
    data class UiState(
        val isLoading: Boolean = false,
        val error: String ?=null,
        val isSuccess: CharacterModel?= null
    )
    sealed class Navigation {
        data object GotoSearchNav: Navigation()
    }
    sealed interface Event{
        data class GotoDetailsEvent(val id: Int): Event
        data object GotoBackEvent: Event
    }
}
