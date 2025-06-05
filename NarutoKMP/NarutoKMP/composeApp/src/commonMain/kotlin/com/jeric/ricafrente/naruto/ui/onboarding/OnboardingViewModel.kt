package com.jeric.ricafrente.naruto.ui.onboarding

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.jeric.ricafrente.naruto.data.repository.PreferenceManager
import kotlinx.coroutines.channels.Channel
import kotlinx.coroutines.delay
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.receiveAsFlow
import kotlinx.coroutines.launch

class OnboardingViewModel (
    private val preferencesManager: PreferenceManager
) : ViewModel() {

    private val _navigation = Channel<OnboardingStateEvent.Navigation>()
    val navigation: Flow<OnboardingStateEvent.Navigation> = _navigation.receiveAsFlow()

    fun onEvent(event: OnboardingStateEvent.Event) {
        when (event) {
            OnboardingStateEvent.Event.AgreeEvent -> {
                viewModelScope.launch {
                    completeOnboarding()
                    _navigation.send(OnboardingStateEvent.Navigation.GotoHomeScreen)
                }
            }
        }
    }

    private fun completeOnboarding() {
        viewModelScope.launch {
            try {
                preferencesManager.saveOnBoardingState(true)
            } catch (e: Exception) {
               e.printStackTrace()
            }
        }
    }
}


object OnboardingStateEvent {
    sealed interface Navigation {
        data object GotoHomeScreen: Navigation
    }
    sealed interface Event{
        data object AgreeEvent: Event
    }
}


