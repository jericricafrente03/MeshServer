package com.jeric.naruto.presentation.components.state

import androidx.compose.ui.graphics.Color
import com.jeric.naruto.ui.theme.Blue300
import com.jeric.naruto.ui.theme.Blue500
import com.jeric.naruto.ui.theme.Green300
import com.jeric.naruto.ui.theme.Green500
import com.jeric.naruto.ui.theme.Red300
import com.jeric.naruto.ui.theme.Red500
import com.jeric.naruto.ui.theme.Yellow300
import com.jeric.naruto.ui.theme.Yellow500

data class CategoryState(
    val title: String,
    val iconUrl: String,
    val startColor: Color,
    val endColor: Color,
) {
    companion object {
        val pokedex = CategoryState(
            title = "Clan",
            iconUrl = "https://i.postimg.cc/Vvt4pGmY/download-3-removebg-preview.png",
            startColor = Red300,
            endColor = Red500,
        )

        val moves = CategoryState(
            title = "Character",
            iconUrl = "https://i.postimg.cc/43wyGsBd/onboarding-3-removebg-preview.png",
            startColor = Yellow300,
            endColor = Yellow500,
        )

        val evolutions = CategoryState(
            title = "Villages",
            iconUrl = "https://raw.githubusercontent.com/M0Coding/Pokedex/main/icons/dna.png",
            startColor = Green300,
            endColor = Green500,
        )

        val locations = CategoryState(
            title = "Teams",
            iconUrl = "https://raw.githubusercontent.com/M0Coding/Pokedex/main/icons/location.png",
            startColor = Blue300,
            endColor = Blue500,
        )
    }
}
