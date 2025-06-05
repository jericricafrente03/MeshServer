package com.jeric.ricafrente.naruto.ui.onboarding.components

import narutokmp.composeapp.generated.resources.Res
import narutokmp.composeapp.generated.resources.onboarding_1
import narutokmp.composeapp.generated.resources.onboarding_2
import narutokmp.composeapp.generated.resources.onboarding_3
import org.jetbrains.compose.resources.DrawableResource

data class OnboardingData(
    val title: String,
    val description: String,
    val imageRes: DrawableResource
)

val onboardingPages = listOf(
    OnboardingData(
        title = "Welcome to Naruto App",
        description = "Discover the world of Naruto with our amazing features",
        imageRes = Res.drawable.onboarding_1
    ),
    OnboardingData(
        title = "Explore Characters",
        description = "Learn about your favorite characters and their abilities",
        imageRes = Res.drawable.onboarding_2
    ),
    OnboardingData(
        title = "Start Your Journey",
        description = "Begin your adventure in the ninja world",
        imageRes = Res.drawable.onboarding_3
    )
) 