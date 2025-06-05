package com.jeric.ricafrente.naruto.ui.details_screen

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.aspectRatio
import androidx.compose.foundation.layout.fillMaxHeight
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.layout.widthIn
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.outlined.Bloodtype
import androidx.compose.material.icons.outlined.DateRange
import androidx.compose.material.icons.rounded.ArrowBackIosNew
import androidx.compose.material.icons.rounded.Favorite
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
import androidx.compose.runtime.key
import androidx.compose.runtime.remember
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.draw.rotate
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.ColorFilter
import androidx.compose.ui.graphics.ColorMatrix
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.lifecycle.compose.LocalLifecycleOwner
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.lifecycle.flowWithLifecycle
import androidx.navigation.NavHostController
import coil3.compose.AsyncImage
import coil3.compose.LocalPlatformContext
import coil3.request.ImageRequest
import coil3.request.crossfade
import com.jeric.ricafrente.naruto.ui.helper.LocalSafeArea
import com.jeric.ricafrente.naruto.ui.helper.NarutoErrorMessage
import com.jeric.ricafrente.naruto.ui.helper.NarutoLoadingBar
import com.jeric.ricafrente.naruto.ui.search.components.AbilityItem
import com.jeric.ricafrente.naruto.ui.search.components.CharacterStatsItem
import com.jeric.ricafrente.naruto.ui.theme.Bug
import com.jeric.ricafrente.naruto.ui.theme.Ice
import kotlinx.coroutines.flow.collectLatest
import narutokmp.composeapp.generated.resources.Res
import narutokmp.composeapp.generated.resources.ic_placeholder
import org.jetbrains.compose.resources.painterResource

@Composable
fun GetDetailsScreen(
    viewModel: GetSelectedViewModel,
    navHostController: NavHostController,
) {
    val uiState by viewModel.uiState.collectAsStateWithLifecycle()
    val lifecycleOwner = LocalLifecycleOwner.current

    LaunchedEffect(key1 = viewModel.navigation) {
        viewModel.navigation.flowWithLifecycle(lifecycleOwner.lifecycle)
            .collectLatest { navigation ->
                when (navigation) {
                    GetSelectedStateEvent.Navigation.GotoSearchNav -> {
                        navHostController.popBackStack()
                    }
                }
            }
    }

    GetDetailsContent(
        uiState = uiState,
        event = viewModel::onEvent
    )

}

@Composable
fun GetDetailsContent(
    uiState: GetSelectedStateEvent.UiState,
    event: (GetSelectedStateEvent.Event) -> Unit,
) {
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
                    event = { event(GetSelectedStateEvent.Event.GotoBackEvent)}
                )
            }
        }
    }
}


@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun DetailsItem(
    id: String,
    imageUrl: List<String>?,
    name: String?,
    natureInfo: List<String>?,
    father: String?,
    mother: String?,
    bloodType: String?,
    birthDate: String?,
    event: () -> Unit,
){
    Scaffold(
        topBar = {
            TopAppBar(
                navigationIcon = {
                    IconButton(
                        onClick = { event() }
                    ) {
                        Icon(Icons.Rounded.ArrowBackIosNew, contentDescription = null)
                    }
                },
                title = {
                   name?.let {
                        Text(
                            text = it,
                            color = MaterialTheme.colorScheme.onBackground,
                            style = MaterialTheme.typography.titleLarge.copy(
                                fontWeight = FontWeight.Medium
                            ),
                            textAlign = TextAlign.Center,
                            modifier = Modifier.fillMaxWidth()
                        )
                    }
                },
                actions = {
                    IconButton(
                        onClick = {}
                    ) {
                        Icon(
                            Icons.Rounded.Favorite,
                            contentDescription = "Favorite",
                            tint = MaterialTheme.colorScheme.primary
                        )
                    }
                },
                colors = TopAppBarDefaults.largeTopAppBarColors(
                    containerColor = Color.Transparent
                )
            )
        },
        containerColor = Color.Transparent,
        modifier = Modifier.padding(LocalSafeArea.current),
        content = {
            Box(
                modifier = Modifier
                    .padding(it)
                    .padding(20.dp)
            ){
                LazyColumn(
                    horizontalAlignment = Alignment.CenterHorizontally,
                    verticalArrangement = Arrangement.spacedBy(10.dp)
                ) {
                    item("image") {
                        imageUrl?.let { images ->
                            val imageSource = remember(images) {
                                images.lastOrNull().takeIf { data -> !data.isNullOrBlank() }
                                    ?: Res.drawable.ic_placeholder
                            }
                            AsyncImage(
                                model = ImageRequest.Builder(LocalPlatformContext.current)
                                    .data(imageSource)
                                    .crossfade(true)
                                    .build(),
                                placeholder = painterResource(Res.drawable.ic_placeholder),
                                contentDescription = null,
                                colorFilter = ColorFilter.colorMatrix(ColorMatrix().apply {
                                    setToSaturation(
                                        3f
                                    )
                                }),
                                contentScale = ContentScale.Crop,
                                modifier = Modifier
                                    .widthIn(max = 500.dp)
                                    .fillMaxWidth()
                                    .aspectRatio(1.2f)
                                    .fillMaxHeight()
                            )
                        }
                    }
                    item("name") {
                        name?.let { name ->
                            Text(
                                text = name.replaceFirstChar { capital -> capital.uppercaseChar() },
                                color = MaterialTheme.colorScheme.onBackground,
                                style = MaterialTheme.typography.titleLarge.copy(
                                    fontWeight = FontWeight.Medium
                                ),
                                textAlign = TextAlign.Center,
                                modifier = Modifier.fillMaxWidth()
                            )
                        }
                    }
                    item("id") {
                        Text(
                            text = id,
                            color = MaterialTheme.colorScheme.onBackground.copy(alpha = .6f),
                            style = MaterialTheme.typography.titleMedium.copy(
                                fontWeight = FontWeight.Medium
                            ),
                            textAlign = TextAlign.Center,
                            modifier = Modifier.fillMaxWidth()
                        )
                    }
                    item("parents") {
                        ParentRow(
                            father = father,
                            mother = mother
                        )
                    }
                    item("infos") {
                        CharacterInfos(
                            birthDate = shortenMonthName(birthDate),
                            bloodType = bloodType,
                            modifier = Modifier
                                .padding(top = 18.dp)
                                .fillMaxWidth(.9f)
                        )
                    }
                    item("stats") {
                        CharacterStats(
                            natureType = natureInfo,
                            modifier = Modifier
                                .padding(top = 12.dp)
                                .fillMaxWidth(.9f)
                        )
                    }
                }
            }
        }
    )
}

fun shortenMonthName(fullDate: String?): String? {
    val parts = fullDate?.split(" ")
    if (parts?.size != 2) return fullDate // return as-is if unexpected format

    val month = parts[0]
    val day = parts[1]
    val shortMonth = month.take(3) // "January" → "Jan"
    return "$shortMonth $day"
}

@Composable
fun ParentRow(
    father: String?,
    mother: String?,
    modifier: Modifier = Modifier,
) {
    Row(
        horizontalArrangement = Arrangement.spacedBy(8.dp),
        modifier = modifier,
    ) {
        AbilityItem(
            name = father ?: "No Data",
            containerColor = Bug
        )
        AbilityItem(
            name = mother ?: "No Data",
            containerColor = Ice
        )

    }
}

@Composable
fun CharacterInfos(
    birthDate: String?,
    bloodType: String?,
    modifier: Modifier = Modifier
) {
    Row(
        verticalAlignment = Alignment.CenterVertically,
        modifier = modifier
            .fillMaxWidth()
            .clip(MaterialTheme.shapes.large)
            .background(MaterialTheme.colorScheme.outline.copy(alpha = .2f))
            .padding(horizontal = 12.dp, vertical = 16.dp)
    ) {
        Column(
            horizontalAlignment = Alignment.CenterHorizontally,
            modifier = Modifier.weight(1f)
        ) {
            Row {
                Icon(
                    Icons.Outlined.DateRange,
                    contentDescription = null
                )
                Spacer(modifier = Modifier.width(4.dp))
                Text(
                    text = birthDate ?: "No data",
                    color = MaterialTheme.colorScheme.onBackground,
                    style = MaterialTheme.typography.bodyLarge.copy(
                        fontWeight = FontWeight.Medium
                    ),
                )
            }
            Spacer(Modifier.height(4.dp))
            Text(
                text = "Birthdate",
                color = MaterialTheme.colorScheme.onBackground.copy(.8f),
                style = MaterialTheme.typography.bodyMedium
            )
        }
        Box(
            modifier = Modifier
                .width(1.dp)
                .height(30.dp)
                .background(color = MaterialTheme.colorScheme.outline)
        )

        Column(
            horizontalAlignment = Alignment.CenterHorizontally,
            modifier = Modifier.weight(1f)
        ) {
            Row {
                Icon(
                    Icons.Outlined.Bloodtype,
                    contentDescription = null,
                    modifier = Modifier.rotate(90f)
                )
                Spacer(Modifier.width(4.dp))

                Text(
                    text = bloodType ?: "No data",
                    color = MaterialTheme.colorScheme.onBackground,
                    style = MaterialTheme.typography.bodyLarge.copy(
                        fontWeight = FontWeight.Bold
                    )
                )
            }

            Spacer(Modifier.height(4.dp))

            Text(
                text = "Blood type",
                color = MaterialTheme.colorScheme.onBackground.copy(.8f),
                style = MaterialTheme.typography.bodyMedium
            )
        }
    }
}

@Composable
fun CharacterStats(
    natureType: List<String>?,
    modifier: Modifier = Modifier
) {
    Column(
        modifier = modifier
            .fillMaxWidth()
            .padding(horizontal = 12.dp, vertical = 16.dp)
    ) {
        natureType?.forEach {
            key(it) {
                CharacterStatsItem(
                    jutsu = it,
                    modifier = Modifier.padding(vertical = 8.dp)
                )
            }
        }
    }
}




