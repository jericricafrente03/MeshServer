package com.jeric.ricafrente.naruto.ui.buruto

import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.BoxWithConstraints
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.layout.aspectRatio
import androidx.compose.foundation.layout.fillMaxHeight
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.layout.widthIn
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
import androidx.compose.runtime.remember
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.ColorFilter
import androidx.compose.ui.graphics.ColorMatrix
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextOverflow
import androidx.compose.ui.unit.dp
import androidx.lifecycle.compose.LocalLifecycleOwner
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.lifecycle.flowWithLifecycle
import androidx.navigation.NavHostController
import coil3.compose.AsyncImage
import coil3.compose.LocalPlatformContext
import coil3.request.ImageRequest
import coil3.request.crossfade
import com.jeric.ricafrente.naruto.core.model.buruto.KaraModel
import com.jeric.ricafrente.naruto.ui.details_screen.DetailsItem
import com.jeric.ricafrente.naruto.ui.details_screen.GetSelectedStateEvent
import com.jeric.ricafrente.naruto.ui.helper.LocalSafeArea
import com.jeric.ricafrente.naruto.ui.helper.NarutoErrorMessage
import com.jeric.ricafrente.naruto.ui.helper.NarutoLoadingBar
import com.jeric.ricafrente.naruto.ui.navigation.Routes
import com.jeric.ricafrente.naruto.ui.tailed_beast.getShortDescription
import kotlinx.coroutines.flow.collectLatest
import narutokmp.composeapp.generated.resources.Res
import narutokmp.composeapp.generated.resources.ic_placeholder
import narutokmp.composeapp.generated.resources.onboarding_3
import org.jetbrains.compose.resources.painterResource

@Composable
fun KaraScreen(viewModel: KaraViewModel, navHostController: NavHostController) {
    val kara by viewModel.uiState.collectAsStateWithLifecycle()
    val lifecycleOwner = LocalLifecycleOwner.current

    LaunchedEffect(key1 = viewModel.navigation) {
        viewModel.navigation.flowWithLifecycle(lifecycleOwner.lifecycle)
            .collectLatest { navigation ->
                when (navigation) {
                    KaraViewStateEvent.Navigation.GotoBackNav -> navHostController.popBackStack()
                    is KaraViewStateEvent.Navigation.GotoDetailsNav -> navHostController.navigate(
                        Routes.KaraCharacter(navigation.id.toInt()))
                }
            }
    }
    KaraContent(
        uiState = kara,
        event = viewModel::onEvent
    )
}

@Composable
fun KaraContent(
    uiState: KaraViewStateEvent.UiState,
    event: (KaraViewStateEvent.Event) -> Unit,
) {

    when {
        uiState.isLoading -> NarutoLoadingBar()
        uiState.error != null -> NarutoErrorMessage(message = uiState.error.toString())
        else -> {
            GridDetails(
                uiState = uiState.isSuccess,
                onClick = { event(KaraViewStateEvent.Event.GotoDetailsEvent(it) )},
                onBack = { event(KaraViewStateEvent.Event.GotoBackEvent) }
            )
        }
    }
}

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun GridDetails(
    uiState: List<KaraModel>,
    onClick: (id: String) -> Unit,
    onBack: () -> Unit
){
    Scaffold(
        topBar = {
            TopAppBar(
                title = {
                    Text(
                        text = "Buroto",
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
                            modifier = Modifier.testTag(data.id)
                        )
                    }
                }
            }
        }
    }
}




@Composable
fun KaraItem(
    onClick: () -> Unit,
    name: String,
    jutsu: List<String>?,
    images: List<String>?,
    natureType: List<String>?,
    tools: List<String>?,
    modifier: Modifier = Modifier
) {
    Column(
        verticalArrangement = Arrangement.spacedBy(8.dp),
        modifier = modifier
            .width(220.dp)
            .clip(MaterialTheme.shapes.medium)
            .clickable { onClick() }
            .padding(10.dp)
    ) {
        val shortBio = remember(jutsu) {
            getShortDescription(
                jutsu = jutsu,
                name = name,
                natureType = natureType,
                tools = tools
            )
        }

        val imageSource = remember(images) {
            images?.lastOrNull().takeIf { data -> !data.isNullOrBlank() }
                ?: Res.drawable.ic_placeholder
        }
        AsyncImage(
            model = ImageRequest.Builder(LocalPlatformContext.current)
                .data(imageSource)
                .crossfade(true)
                .build(),
            placeholder = painterResource(Res.drawable.ic_placeholder),
            contentDescription = null,
            contentScale = ContentScale.Crop,
            modifier = Modifier
                .fillMaxWidth()
                .aspectRatio(1.5f)
                .clip(MaterialTheme.shapes.medium)
        )

        Text(
            text = name,
            color = MaterialTheme.colorScheme.onBackground,
            style = MaterialTheme.typography.titleSmall,
        )

        Text(
            text = shortBio,
            color = MaterialTheme.colorScheme.onBackground.copy(.8f),
            style = MaterialTheme.typography.bodyMedium,
            maxLines = 2,
            overflow = TextOverflow.Ellipsis
        )
    }
}
