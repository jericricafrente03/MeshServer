package com.jeric.ricafrente.naruto.ui.home_page

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
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

class HomeViewModel(
    useCases: UseCases
) : ViewModel() {

    private val _uiState = MutableStateFlow(HomeStateEvent.UiState())
    val uiState: StateFlow<HomeStateEvent.UiState> get() = _uiState.asStateFlow()

    private val _navigation = Channel<HomeStateEvent.Navigation>()
    val navigation: Flow<HomeStateEvent.Navigation> = _navigation.receiveAsFlow()

    init {
        _uiState.update { it.copy(isLoading = true) }
        viewModelScope.launch {
            try {
                delay(1000)
                useCases.getAllTailBeastUseCase().collectLatest { result ->
                    _uiState.update { it.copy(isSuccess = result.data ?: emptyList(), isLoading = false) }
                }
            }catch (e: Exception){
                e.printStackTrace()
                _uiState.update { it.copy(error = e.message) }
            }
        }
    }


    fun onEvent(event: HomeStateEvent.Event){
        when(event){
            HomeStateEvent.Event.GotoAkatsukiEvent -> {
                viewModelScope.launch {
                    _navigation.send(HomeStateEvent.Navigation.GotoAkatsukiNav)
                }
            }
            HomeStateEvent.Event.GotoCharacterEvent -> {
                viewModelScope.launch {
                    _navigation.send(HomeStateEvent.Navigation.GotoCharacterNav)
                }
            }
            HomeStateEvent.Event.GotoKaraEvent -> {
                viewModelScope.launch {
                    _navigation.send(HomeStateEvent.Navigation.GotoKaraNav)
                }
            }
            HomeStateEvent.Event.GotoSearchEvent -> {
                viewModelScope.launch {
                    _navigation.send(HomeStateEvent.Navigation.GotoSearchNav)
                }
            }
            is HomeStateEvent.Event.GotoTailedBeastDetailEvent -> {
                viewModelScope.launch {
                    _navigation.send(HomeStateEvent.Navigation.GotoTailedBeastDetailNav(event.id))
                }
            }

            HomeStateEvent.Event.GotoClanEvent -> {
                viewModelScope.launch {
                    _navigation.send(HomeStateEvent.Navigation.GotoClanNav)
                }
            }

        }
    }
}

object HomeStateEvent {
    data class UiState(
        val isLoading: Boolean = false,
        val error: String ?=null,
        val isSuccess: List<TailedBeastModel> = emptyList()
    )
    sealed class Navigation {
        data object GotoSearchNav: Navigation()
        data object GotoAkatsukiNav: Navigation()
        data object GotoKaraNav: Navigation()
        data object GotoCharacterNav: Navigation()
        data object GotoClanNav: Navigation()
        data class GotoTailedBeastDetailNav(val id: String): Navigation()
    }
    sealed interface Event{
        data object GotoSearchEvent: Event
        data object GotoAkatsukiEvent: Event
        data object GotoKaraEvent: Event
        data object GotoCharacterEvent: Event
        data object GotoClanEvent: Event
        data class GotoTailedBeastDetailEvent(val id: String): Event
    }
}

