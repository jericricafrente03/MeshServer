package com.jeric.naruto.navigation

import kotlinx.serialization.Serializable

//sealed class Routes(val route: String) {
//    data object Splash : Routes("splash")
//    data object Onboarding : Routes("onboarding")
//    data object Home : Routes("home")
//}

@Serializable
sealed class Routes {
    @Serializable
    data object Splash : Routes()
    @Serializable
    data object Onboarding : Routes()
    @Serializable
    data object HomeScreen : Routes()
    @Serializable
    data object SearchScreen : Routes()
    @Serializable
    data object FavoritesScreen : Routes()
}