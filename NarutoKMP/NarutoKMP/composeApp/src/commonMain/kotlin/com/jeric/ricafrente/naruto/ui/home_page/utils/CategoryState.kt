package com.jeric.ricafrente.naruto.ui.home_page.utils

import androidx.compose.ui.graphics.Color
import narutokmp.composeapp.generated.resources.Res
import narutokmp.composeapp.generated.resources.akatsuki
import narutokmp.composeapp.generated.resources.clan
import narutokmp.composeapp.generated.resources.naruto
import narutokmp.composeapp.generated.resources.village
import org.jetbrains.compose.resources.DrawableResource

data class CategoryState(
    val title: String,
    val iconUrl: DrawableResource,
    val startColor: Color,
    val endColor: Color,
) {
    companion object {
        val clan = CategoryState(
            title = "Clan",
            iconUrl = Res.drawable.clan,
            startColor = Color(0xFFC0392B),
            endColor = Color(0xFF96281B),
        )

        val character = CategoryState(
            title = "Character",
            iconUrl = Res.drawable.naruto,
            startColor = Color(0xFFF39C12),
            endColor = Color(0xFFD68910),
        )

        val villages = CategoryState(
            title = "Villages",
            iconUrl = Res.drawable.village,
            startColor = Color(0xFF138D75),
            endColor = Color(0xFF117A65),
        )

        val akatsuki = CategoryState(
            title = "Akatsuki",
            iconUrl = Res.drawable.akatsuki,
            startColor = Color(0xFF2C3E50),
            endColor = Color(0xFF1A252F),
        )
    }
}