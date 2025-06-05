package com.jeric.ricafrente.naruto.ui.character.details

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

@Composable
fun GetDetailsCharacterScreen(
    viewModel: GetSelectedCharacterViewModel,
    navHostController: NavHostController,
) {
    val uiState by viewModel.uiState.collectAsStateWithLifecycle()
    val lifecycleOwner = LocalLifecycleOwner.current

    LaunchedEffect(key1 = viewModel.navigation) {
        viewModel.navigation.flowWithLifecycle(lifecycleOwner.lifecycle)
            .collectLatest { navigation ->
                when (navigation) {
                    GetSelectedCharacterStateEvent.Navigation.GotoSearchNav -> navHostController.popBackStack()
                }
            }
    }
    when {
        uiState.isLoading -> NarutoLoadingBar()
        uiState.error != null -> NarutoErrorMessage(message = uiState.error.toString())
        else -> {
            uiState.isSuccess?.let {
                DetailsItem(
                    id = it.id,
                    imageUrl = it.images,
                    natureInfo = it.natureType,
                    name = it.name,
                    father = it.father,
                    mother = it.mother,
                    bloodType = it.bloodType,
                    birthDate = it.birthdate,
                    event = { viewModel.onEvent(GetSelectedCharacterStateEvent.Event.GotoBackEvent)}
                )
            }
        }
    }
}