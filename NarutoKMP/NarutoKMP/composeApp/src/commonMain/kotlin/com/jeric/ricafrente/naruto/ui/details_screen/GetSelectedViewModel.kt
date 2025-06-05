package com.jeric.ricafrente.naruto.ui.details_screen

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

class GetSelectedViewModel(
    private val useCases: UseCases
) : ViewModel() {
    private val _uiState = MutableStateFlow(GetSelectedStateEvent.UiState())
    val uiState: StateFlow<GetSelectedStateEvent.UiState> get() = _uiState.asStateFlow()

    private val _navigation = Channel<GetSelectedStateEvent.Navigation>()
    val navigation: Flow<GetSelectedStateEvent.Navigation> = _navigation.receiveAsFlow()


    fun onEvent(event: GetSelectedStateEvent.Event){
        when(event){
            is GetSelectedStateEvent.Event.GotoDetailsEvent -> getDetails(event.id)
            GetSelectedStateEvent.Event.GotoBackEvent -> {
                viewModelScope.launch {
                    _navigation.send(GetSelectedStateEvent.Navigation.GotoSearchNav)
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

object GetSelectedStateEvent {
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
