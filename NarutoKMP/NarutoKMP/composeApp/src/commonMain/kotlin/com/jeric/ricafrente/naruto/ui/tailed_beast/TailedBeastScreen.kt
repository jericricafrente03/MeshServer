package com.jeric.ricafrente.naruto.ui.tailed_beast

import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.lifecycle.compose.LocalLifecycleOwner
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.lifecycle.flowWithLifecycle
import androidx.navigation.NavHostController
import com.jeric.ricafrente.naruto.ui.details_screen.DetailsItem
import com.jeric.ricafrente.naruto.ui.helper.NarutoErrorMessage
import com.jeric.ricafrente.naruto.ui.helper.NarutoLoadingBar
import kotlinx.coroutines.flow.collectLatest

@ExperimentalMaterial3Api
@Composable
fun TailedBeastScreen(
    viewModel: GetSelectedTailedBeastViewModel,
    navHostController: NavHostController,
) {
    val uiState by viewModel.uiState.collectAsStateWithLifecycle()
    val lifecycleOwner = LocalLifecycleOwner.current

    LaunchedEffect(key1 = viewModel.navigation) {
        viewModel.navigation.flowWithLifecycle(lifecycleOwner.lifecycle)
            .collectLatest { navigation ->
                when (navigation) {
                    GetSelectedTailedBeastStateEvent.Navigation.GotoHomeNav ->
                        navHostController.popBackStack()
                }
            }
    }

    when {
        uiState.isLoading -> NarutoLoadingBar()
        uiState.error != null -> NarutoErrorMessage(message = uiState.error.toString())
        else -> {
            uiState.isSuccess?.let {
                TailedBeastContent(
                    onBack = { viewModel.onEvent(GetSelectedTailedBeastStateEvent.Event.GotoBackEvent) },
                    id = it.id,
                    name = it.name,
                    images = it.images,
                    jutsu = it.jutsu,
                    natureType = it.natureType,
                    tools = it.tools,
                    uniqueTraits = it.uniqueTraits
                )
            }
        }
    }
}

