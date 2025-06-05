package com.jeric.ricafrente.naruto

import androidx.compose.ui.window.ComposeUIViewController
import com.jeric.ricafrente.naruto.core.di.initKoin

fun MainViewController() = ComposeUIViewController(
    configure = { initKoin() }
) { App() }