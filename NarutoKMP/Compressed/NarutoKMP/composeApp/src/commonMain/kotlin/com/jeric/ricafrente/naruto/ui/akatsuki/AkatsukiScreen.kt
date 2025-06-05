package com.jeric.ricafrente.naruto.ui.akatsuki

import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.BoxWithConstraints
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.lazy.grid.GridCells
import androidx.compose.foundation.lazy.grid.LazyVerticalGrid
import androidx.compose.foundation.lazy.grid.items
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.rounded.ArrowBackIosNew
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.Icon
import androidx.compose.material3.IconButton
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.material3.TopAppBar
import androidx.compose.material3.TopAppBarDefaults
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.lifecycle.compose.LocalLifecycleOwner
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.lifecycle.flowWithLifecycle
import androidx.navigation.NavHostController
import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.ui.buruto.KaraItem
import com.jeric.ricafrente.naruto.ui.helper.LocalSafeArea
import com.jeric.ricafrente.naruto.ui.helper.NarutoErrorMessage
import com.jeric.ricafrente.naruto.ui.helper.NarutoLoadingBar
import com.jeric.ricafrente.naruto.ui.navigation.Routes
import kotlinx.coroutines.flow.collectLatest

@Composable
fun AkatsukiScreen(viewModel: AkatsukiViewModel, navHostController: NavHostController) {
    val akatsuki by viewModel.uiState.collectAsStateWithLifecycle()
    val lifecycleOwner = LocalLifecycleOwner.current

    LaunchedEffect(key1 = viewModel.navigation) {
        viewModel.navigation.flowWithLifecycle(lifecycleOwner.lifecycle)
            .collectLatest { navigation ->
                when (navigation) {
                    AkatsukiStateEvent.Navigation.GotoBackNav -> navHostController.popBackStack()
                    is AkatsukiStateEvent.Navigation.GotoDetailsNav ->
                        navHostController.navigate(
                            Routes.AkatsukiCharacter(
                                navigation.id.toInt()
                            )
                        )
                }
            }
    }
    AkatsukiContent(
        uiState = akatsuki,
        event = viewModel::onEvent
    )
}

@Composable
fun AkatsukiContent(
    uiState: AkatsukiStateEvent.UiState,
    event: (AkatsukiStateEvent.Event) -> Unit,
) {
    when {
        uiState.isLoading -> NarutoLoadingBar()
        uiState.error != null -> NarutoErrorMessage(message = uiState.error.toString())
        else -> {
            AkatsukiDetails(
                uiState = uiState.isSuccess,
                onClick = { event(AkatsukiStateEvent.Event.GotoDetailsEvent(it) )},
                onBack = { event(AkatsukiStateEvent.Event.GotoBackEvent) }
            )
        }
    }
}

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun AkatsukiDetails(
    uiState: List<AkatsukiModel>,
    onClick: (id: String) -> Unit,
    onBack: () -> Unit
){
    Scaffold(
        topBar = {
            TopAppBar(
                title = {
                    Text(
                        text = "Akatsuki",
                        color = MaterialTheme.colorScheme.onBackground,
                        style = MaterialTheme.typography.titleLarge.copy(
                            fontWeight = FontWeight.Bold
                        ),
                        modifier = Modifier
                            .padding(horizontal = 20.dp)
                            .padding(top = 20.dp, bottom = 6.dp)
                    )
                },
                navigationIcon = {
                    IconButton(
                        onClick = { onBack() }, modifier = Modifier.testTag("kara_back"),
                    ) {
                        Icon(Icons.Rounded.ArrowBackIosNew, contentDescription = null)
                    }
                },
                colors = TopAppBarDefaults.largeTopAppBarColors(
                    containerColor = MaterialTheme.colorScheme.background
                )
            )
        },
        modifier = Modifier.padding(LocalSafeArea.current)
    ) { paddingValue ->
        Box(
            modifier = Modifier.padding(paddingValue)
        ) {
            BoxWithConstraints {
                val columns = when(maxWidth) {
                    in 0.dp..349.dp -> 1
                    in 350.dp..599.dp -> 2
                    in 600.dp..899.dp -> 3
                    in 900.dp..1199.dp -> 4
                    else -> 5
                }

                LazyVerticalGrid(
                    columns = GridCells.Fixed(columns),
                    horizontalArrangement = Arrangement.spacedBy(12.dp),
                    verticalArrangement = Arrangement.spacedBy(12.dp),
                    contentPadding = PaddingValues(20.dp),
                    modifier = Modifier.testTag("kara_col")
                ) {
                    items(items = uiState, key = {it.id}){ data ->
                        KaraItem(
                            onClick = { onClick(data.id) },
                            name = data.name,
                            jutsu = data.jutsu,
                            images = data.images,
                            natureType = data.natureType,
                            tools = data.tools,
                        )
                    }
                }
            }
        }
    }
}

