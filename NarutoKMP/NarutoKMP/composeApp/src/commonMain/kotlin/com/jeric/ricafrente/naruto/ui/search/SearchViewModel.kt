package com.jeric.ricafrente.naruto.ui.search

import androidx.compose.runtime.mutableStateOf
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import androidx.paging.PagingData
import androidx.paging.cachedIn
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


class SearchViewModel(
    private val useCases: UseCases
) : ViewModel() {

    private val _searchedHeroes = MutableStateFlow<PagingData<CharacterModel>>(PagingData.empty())
    val searchedHeroes = _searchedHeroes

    private val _searchQuery = mutableStateOf("")
    val searchQuery = _searchQuery

    private val _navigation = Channel<SearchOnlineStateEvent.Navigation>()
    val navigation: Flow<SearchOnlineStateEvent.Navigation> = _navigation.receiveAsFlow()

    fun updateSearchQuery(query: String) {
        _searchQuery.value = query
    }

    fun searchCharacter(query: String) = viewModelScope.launch {
        useCases.searchHeroesUseCase(query = query).cachedIn(viewModelScope).collect { result ->
            _searchedHeroes.value = result
        }
    }

    fun onEvent(event: SearchOnlineStateEvent.Event){
        when(event){
            is SearchOnlineStateEvent.Event.GotoDetailEvent -> {
                viewModelScope.launch {
                    _navigation.send(SearchOnlineStateEvent.Navigation.GotoDetailNav(event.id))
                }
            }
            SearchOnlineStateEvent.Event.GotoHome -> SearchOnlineStateEvent.Navigation.BackNav
        }
    }
}

object SearchOnlineStateEvent {
    sealed class Navigation {
        data object BackNav: Navigation()
        data class GotoDetailNav(val id: String): Navigation()
    }
    sealed interface Event{
        data object GotoHome: Event
        data class GotoDetailEvent(val id: String): Event
    }
}