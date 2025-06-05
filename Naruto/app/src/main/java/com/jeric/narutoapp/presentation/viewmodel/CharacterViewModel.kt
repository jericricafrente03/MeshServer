package com.jeric.narutoapp.presentation.viewmodel

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.jeric.narutoapp.data.model.NarutoCharacter
import com.jeric.narutoapp.data.repository.CharacterRepository
import kotlinx.coroutines.flow.SharingStarted
import kotlinx.coroutines.flow.stateIn

class CharacterViewModel(
    private val repository: CharacterRepository
) : ViewModel() {

    val characters = repository.getCharacters()
        .stateIn(
            scope = viewModelScope,
            started = SharingStarted.WhileSubscribed(5000),
            initialValue = PagingData.empty()
        )
} 