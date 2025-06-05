package com.jeric.naruto.data.model

import com.jeric.naruto.R

data class OnboardingData(
    val title: String,
    val description: String,
    val imageRes: Int
)

val onboardingPages = listOf(
    OnboardingData(
        title = "Welcome to Naruto App",
        description = "Discover the world of Naruto with our amazing features",
        imageRes = R.drawable.onboarding_1
    ),
    OnboardingData(
        title = "Explore Characters",
        description = "Learn about your favorite characters and their abilities",
        imageRes = R.drawable.onboarding_2
    ),
    OnboardingData(
        title = "Start Your Journey",
        description = "Begin your adventure in the ninja world",
        imageRes = R.drawable.onboarding_3
    )
) 