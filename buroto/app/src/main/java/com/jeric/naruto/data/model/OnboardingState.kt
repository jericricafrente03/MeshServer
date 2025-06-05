package com.jeric.naruto.data.model

sealed class OnboardingState {
    data object Initial : OnboardingState()
    data object Loading : OnboardingState()
    data class Success(val isOnboardingCompleted: Boolean) : OnboardingState()
    data class Error(val message: String) : OnboardingState()
} 