package com.jeric.ricafrente.naruto.ui.clan

import androidx.compose.foundation.Image
import androidx.compose.foundation.background
import androidx.compose.foundation.interaction.MutableInteractionSource
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.BoxWithConstraints
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.layout.aspectRatio
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.offset
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
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.alpha
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.text.SpanStyle
import androidx.compose.ui.text.buildAnnotatedString
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.text.style.TextDecoration
import androidx.compose.ui.text.style.TextOverflow
import androidx.compose.ui.text.withStyle
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.compose.LocalLifecycleOwner
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.lifecycle.flowWithLifecycle
import androidx.navigation.NavHostController
import com.jeric.ricafrente.naruto.core.model.clan.ClanModel
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.ui.helper.LocalSafeArea
import com.jeric.ricafrente.naruto.ui.helper.NarutoErrorMessage
import com.jeric.ricafrente.naruto.ui.helper.NarutoLoadingBar
import com.jeric.ricafrente.naruto.ui.home_page.HomeDetails
import com.jeric.ricafrente.naruto.ui.home_page.HomeStateEvent
import com.jeric.ricafrente.naruto.ui.theme.Green300
import com.jeric.ricafrente.naruto.ui.theme.Green500
import com.jeric.ricafrente.naruto.ui.theme.Yellow300
import com.jeric.ricafrente.naruto.ui.theme.Yellow500
import com.jeric.ricafrente.naruto.ui.theme.Red300
import com.jeric.ricafrente.naruto.ui.theme.Red500
import com.jeric.ricafrente.naruto.ui.theme.Blue300
import com.jeric.ricafrente.naruto.ui.theme.Blue500
import com.jeric.ricafrente.naruto.ui.theme.titleColor
import kotlinx.coroutines.flow.collectLatest
import narutokmp.composeapp.generated.resources.Res
import narutokmp.composeapp.generated.resources.onboarding_3
import org.jetbrains.compose.resources.painterResource


@Composable
fun ClanScreen(viewModel: ClanViewModel, navHostController: NavHostController) {
    val uiState by viewModel.uiState.collectAsStateWithLifecycle()

    val lifecycleOwner = LocalLifecycleOwner.current

    LaunchedEffect(key1 = viewModel.navigation) {
        viewModel.navigation.flowWithLifecycle(lifecycleOwner.lifecycle)
            .collectLatest { navigation ->
                when (navigation) {
                    ClanViewStateEvent.Navigation.GotoHomeNav -> navHostController.popBackStack()
                }
            }
    }

    ClanContent(
        uiState = uiState,
        event = { viewModel.onEvent(ClanViewStateEvent.Event.GotoHomeEvent) }
    )
}

@Composable
fun ClanContent(uiState: ClanViewStateEvent.UiState, event: (ClanViewStateEvent.Event) -> Unit) {
    when {
        uiState.isLoading -> NarutoLoadingBar()
        uiState.error != null -> NarutoErrorMessage(message = uiState.error.toString())
        else -> {
            ClanDetails(
                uiState = uiState.isSuccess,
                event = event,
            )
        }
    }
}


@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun ClanDetails(
    uiState: List<ClanModel>,
    event: (ClanViewStateEvent.Event) -> Unit,
){
    Scaffold(
        topBar = {
            TopAppBar(
                title = {
                    Text(
                        text = "Naruto Book Clan",
                        color = MaterialTheme.colorScheme.onBackground,
                        style = MaterialTheme.typography.titleLarge.copy(
                            fontWeight = FontWeight.Medium
                        ),
                        modifier = Modifier
                            .padding(horizontal = 20.dp)
                            .padding(top = 20.dp, bottom = 6.dp)
                    )
                },
                navigationIcon = {
                    IconButton(
                        onClick = { event(ClanViewStateEvent.Event.GotoHomeEvent) }, modifier = Modifier.testTag("clan_back"),
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
                    modifier = Modifier
                        .testTag("clan_col")
                ) {
                    items(items = uiState, key = {it.id}){ data ->
                        ClanContentItem(
                            modifier = Modifier.testTag(data.id),
                            clanModel = data
                        )
                    }
                }
            }
        }
    }
}


@Composable
fun ClanContentItem(
    modifier: Modifier = Modifier,
    clanModel: ClanModel,
    contentColor: Color = titleColor
) {

    val brush = remember {
        Brush.linearGradient(
            listOf(
                listOf(
                    Red300,
                    Red500,
                ),
                listOf(
                    Yellow300,
                    Yellow500,
                ),
                listOf(
                    Green300,
                    Green500,
                ),
                listOf(
                    Blue300,
                    Blue500,
                ),
            ).random()
        )
    }

    Box(
        modifier = modifier
            .aspectRatio(1.2f),
        contentAlignment = Alignment.Center
    ) {
        Box(
            modifier = Modifier
                .fillMaxSize()
                .background(brush = brush, alpha = .4f, shape = MaterialTheme.shapes.medium)
        )
        Text(
            modifier = Modifier
                .padding(all = 6.dp)
                .align(Alignment.BottomStart)
                .alpha(0.3f),
            textAlign = TextAlign.Center,
            text = clanModel.name.trim(),
            maxLines = 1,
            fontSize = MaterialTheme.typography.titleMedium.fontSize,
            fontWeight = FontWeight.Bold,
            color = contentColor,
            overflow = TextOverflow.Ellipsis
        )

        Text(
            modifier = Modifier
                .padding(all = 6.dp)
                .align(Alignment.TopStart)
                .alpha(0.3f),
            textAlign = TextAlign.Center,
            text = clanModel.id,
            fontWeight = FontWeight.Black,
            fontSize = 50.sp,
        )

        Image(
            modifier = Modifier
                .fillMaxSize()
                .offset(35.dp, (-20).dp),
            painter = painterResource(resource = Res.drawable.onboarding_3),
            contentDescription = "Product Image"
        )
    }
}