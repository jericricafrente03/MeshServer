package com.jeric.naruto.presentation.onboarding

import androidx.compose.foundation.pager.PagerState
import com.jeric.naruto.data.model.OnboardingData

data class OnboardingState(
    val currentPage: Int = 0,
    val isLoading: Boolean = false,
    val error: String? = null,
    val isOnboardingCompleted: Boolean = false,
    val pages: List<OnboardingData> = emptyList()
) 