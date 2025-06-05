package com.jeric.ricafrente.naruto.ui.theme

import androidx.compose.material3.Typography
import androidx.compose.runtime.Composable
import androidx.compose.ui.text.TextStyle
import androidx.compose.ui.text.font.Font
import androidx.compose.ui.text.font.FontFamily
import androidx.compose.ui.text.font.FontStyle
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.LineHeightStyle
import androidx.compose.ui.unit.sp
import narutokmp.composeapp.generated.resources.Res
import narutokmp.composeapp.generated.resources.golos_medium
import narutokmp.composeapp.generated.resources.golos_regular
import org.jetbrains.compose.resources.Font


@Composable
fun fontFamily() = FontFamily(
    Font(Res.font.golos_regular, FontWeight.Normal),
    Font(Res.font.golos_medium, FontWeight.Medium)
)

@Composable
fun getTypography(): Typography {

    val defaultTextStyle = TextStyle(
        fontFamily = fontFamily(),
        lineHeightStyle = LineHeightStyle(
            alignment = LineHeightStyle.Alignment.Center,
            trim = LineHeightStyle.Trim.None,
        )
    )

    return Typography(
        displayLarge = defaultTextStyle.copy(
            fontSize = 57.sp,
            lineHeight = 64.sp,
            letterSpacing = (-0.25).sp,
        ),
        displayMedium = defaultTextStyle.copy(
            fontSize = 45.sp,
            lineHeight = 52.sp,
            letterSpacing = 0.sp,
        ),
        displaySmall = defaultTextStyle.copy(
            fontSize = 36.sp,
            lineHeight = 44.sp,
            letterSpacing = 0.sp,
        ),
        headlineLarge = defaultTextStyle.copy(
            fontSize = 32.sp,
            lineHeight = 40.sp,
            letterSpacing = 0.sp,
        ),
        headlineMedium = defaultTextStyle.copy(
            fontSize = 28.sp,
            lineHeight = 36.sp,
            letterSpacing = 0.sp,
        ),
        headlineSmall = defaultTextStyle.copy(
            fontSize = 24.sp,
            lineHeight = 32.sp,
            letterSpacing = 0.sp,
        ),
        titleLarge = defaultTextStyle.copy(
            fontSize = 28.sp,
            lineHeight = 28.sp,
            letterSpacing = 0.sp,
        ),
        titleMedium = defaultTextStyle.copy(
            fontSize = 18.sp,
            lineHeight = 24.sp,
            letterSpacing = 0.15.sp,
            fontWeight = FontWeight.Medium,
        ),
        titleSmall = defaultTextStyle.copy(
            fontSize = 14.sp,
            lineHeight = 20.sp,
            letterSpacing = 0.1.sp,
            fontWeight = FontWeight.Medium,
        ),
        labelLarge = defaultTextStyle.copy(
            fontSize = 14.sp,
            lineHeight = 20.sp,
            letterSpacing = 0.1.sp,
            fontWeight = FontWeight.Medium,
        ),
        labelMedium = defaultTextStyle.copy(
            fontSize = 12.sp,
            lineHeight = 16.sp,
            letterSpacing = 0.5.sp,
            fontWeight = FontWeight.Medium,
        ),
        labelSmall = defaultTextStyle.copy(
            fontSize = 11.sp,
            lineHeight = 16.sp,
            letterSpacing = 0.5.sp,
            fontWeight = FontWeight.Medium,
        ),
        bodyLarge = defaultTextStyle.copy(
            fontSize = 20.sp,
            lineHeight = 24.sp,
            letterSpacing = 0.5.sp,
        ),
        bodyMedium = defaultTextStyle.copy(
            fontSize = 14.sp,
            lineHeight = 20.sp,
            letterSpacing = 0.25.sp,
        ),
        bodySmall = defaultTextStyle.copy(
            fontSize = 12.sp,
            lineHeight = 16.sp,
            letterSpacing = 0.4.sp,
        ),
    )
}