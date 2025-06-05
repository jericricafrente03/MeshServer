package com.jeric.ricafrente.naruto.ui.character

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import androidx.paging.PagingData
import app.cash.paging.cachedIn
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.core.use_cases.UseCases
import kotlinx.coroutines.channels.Channel
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.SharingStarted
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.receiveAsFlow
import kotlinx.coroutines.flow.stateIn
import kotlinx.coroutines.launch

class CharacterViewModel(
    useCases: UseCases
): ViewModel() {

    val characters: StateFlow<PagingData<CharacterModel>> = useCases.getAllCharacterUseCase()
        .cachedIn(viewModelScope)
        .stateIn(
            scope = viewModelScope,
            started = SharingStarted.WhileSubscribed(stopTimeoutMillis = 5000),
            initialValue = PagingData.empty()
        )

    private val _navigation = Channel<CharacterStateEvent.Navigation>()
    val navigation: Flow<CharacterStateEvent.Navigation> = _navigation.receiveAsFlow()

    fun onEvent(event: CharacterStateEvent.Event){
        when(event){
            CharacterStateEvent.Event.GotoHomeEvent -> {
                viewModelScope.launch {
                    _navigation.send(CharacterStateEvent.Navigation.GotoHomeNav)
                }
            }

            is CharacterStateEvent.Event.GotoDetailsEvent -> {
                viewModelScope.launch {
                    _navigation.send(CharacterStateEvent.Navigation.GotoDetails(event.id))
                }
            }
        }
    }

}

object CharacterStateEvent {
    sealed class Navigation {
        data object GotoHomeNav: Navigation()
        data class GotoDetails(val id: String): Navigation()
    }
    sealed interface Event{
        data object GotoHomeEvent: Event
        data class GotoDetailsEvent(val id: String): Event
    }
}
