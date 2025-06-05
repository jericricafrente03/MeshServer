package com.jeric.ricafrente.naruto

import androidx.compose.foundation.isSystemInDarkTheme
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Surface
import androidx.compose.runtime.Composable
import androidx.navigation.compose.rememberNavController
import com.jeric.ricafrente.naruto.ui.navigation.NavGraphSetup
import com.jeric.ricafrente.naruto.ui.theme.NarutoTheme
import org.jetbrains.compose.ui.tooling.preview.Preview

@Composable
@Preview
fun App() {
    NarutoTheme {
        val navController = rememberNavController()
        Surface(
            color = MaterialTheme.colorScheme.background
        ) {
            NavGraphSetup(navController = navController)
        }
    }
}
