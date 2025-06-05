package com.jeric.ricafrente.naruto.ui.splash_screen

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.jeric.ricafrente.naruto.data.repository.PreferenceManager
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.collectLatest
import kotlinx.coroutines.launch

class SplashViewModel (
    private val preferencesManager: PreferenceManager
) : ViewModel() {

    private val _isCompleted = MutableStateFlow(false)
    val isCompleted: StateFlow<Boolean> = _isCompleted.asStateFlow()

    init {
        viewModelScope.launch {
            preferencesManager.readOnBoardingState().collectLatest { isCompleted ->
                _isCompleted.value = isCompleted
            }
        }
    }

}
