@file:OptIn(ExperimentalMaterial3Api::class)

package com.jeric.naruto.presentation.home

import android.util.Log
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.fillMaxHeight
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.rounded.Menu
import androidx.compose.material.icons.rounded.Search
import androidx.compose.material3.DrawerValue
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.HorizontalDivider
import androidx.compose.material3.Icon
import androidx.compose.material3.IconButton
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.ModalNavigationDrawer
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Surface
import androidx.compose.material3.Text
import androidx.compose.material3.TextField
import androidx.compose.material3.TextFieldDefaults
import androidx.compose.material3.TopAppBar
import androidx.compose.material3.TopAppBarDefaults
import androidx.compose.material3.rememberDrawerState
import androidx.compose.runtime.Composable
import androidx.compose.runtime.remember
import androidx.compose.runtime.rememberCoroutineScope
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.text.style.TextOverflow
import androidx.compose.ui.unit.dp
import androidx.navigation.NavHostController
import androidx.paging.LoadState
import androidx.paging.compose.LazyPagingItems
import coil.compose.AsyncImage
import coil.request.ImageRequest
import com.jeric.naruto.R
import com.jeric.naruto.domain.model.character.CharacterModel
import com.jeric.naruto.domain.model.tail_beast.TailedBeastModel
import com.jeric.naruto.presentation.components.CategoryButton
import com.jeric.naruto.presentation.components.EmptyScreen
import com.jeric.naruto.presentation.components.ShimmerEffect
import com.jeric.naruto.presentation.components.SnackbarEvent
import com.jeric.naruto.presentation.helper.LocalSafeArea
import com.jeric.naruto.ui.theme.EXTRA_SMALL_PADDING
import com.jeric.naruto.ui.theme.HERO_ITEM_HEIGHT
import com.jeric.naruto.ui.theme.LARGE_PADDING
import com.jeric.naruto.ui.theme.MEDIUM_PADDING
import com.jeric.naruto.ui.theme.SMALL_PADDING
import com.jeric.naruto.presentation.components.state.CategoryState
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.launch

@Composable
fun HomeScreen(
    navController: NavHostController,
    snackbarEvent: Flow<SnackbarEvent>,
    onImageClick: (String) -> Unit,
  /*  beast: List<TailedBeastModel>,*/
    selectedItem: Pair<String, ImageVector>,
    items: List<Pair<String, ImageVector>>,
) {
    val scope = rememberCoroutineScope()
    val drawerState = rememberDrawerState(DrawerValue.Closed)

    ModalNavigationDrawer(
        drawerState = drawerState,
        drawerContent = {
            MainModalDrawerSheet(
                items = items,
                selectedItem = selectedItem,
                onItemsClick = { item ->
                    scope.launch { drawerState.close() }
                }
            )
        },
        content = {
            Scaffold(
                topBar = {
                    TopAppBar(
                        title = {},
                        navigationIcon = {
                            IconButton(
                                onClick = {
                                    scope.launch { drawerState.open() }
                                },
                            ) {
                                Icon(Icons.Rounded.Menu, contentDescription = null)
                            }
                        },
                        colors = TopAppBarDefaults.largeTopAppBarColors(
                            containerColor = MaterialTheme.colorScheme.background
                        )
                    )
                },
                modifier = Modifier.padding(LocalSafeArea.current)
            ) { paddingValues ->
                MainContent(
                    modifier = Modifier.padding(paddingValues)
                )
            }
        }
    )
}




@Composable
fun MainContent(modifier: Modifier = Modifier) {
    val containerColor = MaterialTheme.colorScheme.surface.copy(alpha = .5f)
    Column(modifier = modifier.verticalScroll(rememberScrollState())) {
        Text(
            text ="Which Naruto character are you looking for?",
            color = MaterialTheme.colorScheme.onBackground,
            style = MaterialTheme.typography.displaySmall.copy(
                fontWeight = FontWeight.ExtraBold
            ),
            modifier = Modifier.padding(horizontal = 20.dp, vertical = 20.dp)
        )
        TextField(
            value = "",
            onValueChange = {},
            placeholder = { Text(text = "Search Character") },
            leadingIcon = {
                IconButton(onClick = {}) {
                    Icon(Icons.Rounded.Search, contentDescription = "Search Character")
                }
            },
            colors = TextFieldDefaults.colors(
                focusedContainerColor = containerColor,
                unfocusedContainerColor = containerColor,
                disabledContainerColor = containerColor,
                focusedIndicatorColor = Color.Transparent,
                unfocusedIndicatorColor = Color.Transparent,
                focusedLeadingIconColor = MaterialTheme.colorScheme.surface,
                unfocusedLeadingIconColor = MaterialTheme.colorScheme.surface,
                focusedPlaceholderColor = MaterialTheme.colorScheme.surface,
                unfocusedPlaceholderColor = MaterialTheme.colorScheme.surface,
            ),
            shape = MaterialTheme.shapes.extraLarge,
            modifier = Modifier
                .fillMaxWidth()
                .padding(horizontal = 20.dp, vertical = 20.dp)
        )
        Row(
            horizontalArrangement = Arrangement.spacedBy(20.dp),
            modifier = Modifier.padding(horizontal = 20.dp, vertical = 10.dp)
        ) {
            CategoryButton(
                onClick = {

                },
                categoryState = CategoryState.pokedex,
                modifier = Modifier.weight(1f),
            )

            CategoryButton(
                onClick = {

                },
                categoryState = CategoryState.moves,
                modifier = Modifier.weight(1f),
            )
        }


        Row(
            horizontalArrangement = Arrangement.spacedBy(20.dp),
            modifier = Modifier.padding(horizontal = 20.dp, vertical = 10.dp)
        ) {
            CategoryButton(
                onClick = {

                },
                categoryState = CategoryState.evolutions,
                modifier = Modifier.weight(1f),
            )

            CategoryButton(
                onClick = {

                },
                categoryState = CategoryState.locations,
                modifier = Modifier.weight(1f),
            )
        }

        Text(
            text = "Characters",
            color = MaterialTheme.colorScheme.onBackground,
            style = MaterialTheme.typography.titleLarge.copy(
                fontWeight = FontWeight.Bold
            ),
            modifier = Modifier
                .padding(horizontal = 20.dp)
                .padding(top = 20.dp, bottom = 6.dp)
        )

        HorizontalDivider(
            modifier = Modifier
                .fillMaxWidth()
                .padding(horizontal = 20.dp),
            color = MaterialTheme.colorScheme.outline.copy(alpha = .4f)
        )


    }
}



@Composable
fun ListContent(
    padding: PaddingValues,
    heroes: LazyPagingItems<CharacterModel>,
) {
    val result = handlePagingResult(character = heroes)

    if (result){
        LazyColumn(
            modifier = Modifier
                .padding(paddingValues = padding),
            contentPadding = PaddingValues(all = SMALL_PADDING),
            verticalArrangement = Arrangement.spacedBy(SMALL_PADDING)
        ) {
            items(
                items = heroes.itemSnapshotList.items,
                key = { hero ->
                    hero.id
                }
            ) { index ->
                CharacterItem(index)
            }
        }
    }
}

fun CharacterModel.getShortBio(): String {
    val birth = personal?.birthdate ?: "an unknown date"
    val sex = personal?.sex ?: "Unknown"
    val blood = personal?.bloodType ?: "Unknown"
    val father = family?.father ?: "an unknown father"
    val mother = family?.mother ?: "an unknown mother"
    val jutsuCount = jutsu?.size ?: 0
    val natureCount = natureType?.size ?: 0

    return "$name was born on $birth. A $sex ninja with blood type $blood, " +
            "$name is the child of $father and $mother. Known for mastering $jutsuCount jutsu " +
            "and wielding $natureCount chakra nature types, $name is a notable figure in the Naruto universe."
}

@Composable
fun CharacterItem(character: CharacterModel) {
    val context = LocalContext.current
    val imageRequest = ImageRequest.Builder(context)
            .data(if (character.images?.isNotEmpty() == true) character.images.last() else R.drawable.ic_placeholder)
            .placeholder(drawableResId = R.drawable.ic_placeholder)
            .error(drawableResId = R.drawable.ic_placeholder)
            .crossfade(true)
            .build()

    val shortBio = remember(character) {
        character.getShortBio()
    }

    Box(
        modifier = Modifier
            .fillMaxWidth()
            .height(HERO_ITEM_HEIGHT)
            .padding(horizontal = SMALL_PADDING),
        contentAlignment = Alignment.BottomStart
    ) {
        Surface(
            shape = RoundedCornerShape(size = LARGE_PADDING),
            modifier = Modifier.fillMaxSize()
        ) {
            AsyncImage(
                model = imageRequest,
                contentDescription = null,
                contentScale = ContentScale.Crop,
                modifier = Modifier.fillMaxSize()
            )
        }
        Surface(
            modifier = Modifier
                .fillMaxHeight(0.45f)
                .fillMaxWidth(),
            color = Color.Black.copy(alpha = 0.5f),
            shape = RoundedCornerShape(
                bottomStart = LARGE_PADDING,
                bottomEnd = LARGE_PADDING
            )
        ) {
            Column(
                modifier = Modifier
                    .fillMaxSize()
                    .padding(all = MEDIUM_PADDING)
            ) {
                Text(
                    text = character.name,
                    fontSize = MaterialTheme.typography.titleLarge.fontSize,
                    fontWeight = FontWeight.Bold,
                    maxLines = 1,
                    overflow = TextOverflow.Ellipsis
                )
                Text(
                    text = shortBio,
                    color = Color.White.copy(alpha = 0.5f),
                    fontSize = MaterialTheme.typography.bodyMedium.fontSize,
                    maxLines = 2,
                    overflow = TextOverflow.Ellipsis,
                    modifier = Modifier.padding(vertical = EXTRA_SMALL_PADDING)
                )
                Row(
                    modifier = Modifier.padding(top = SMALL_PADDING),
                    verticalAlignment = Alignment.CenterVertically
                ) {
//                    RatingWidget(
//                        modifier = Modifier.padding(end = SMALL_PADDING),
//                        rating = 3.3
//                    )
                    Text(
                        text = "(${character.images?.size})",
                        textAlign = TextAlign.Center,
                        color = Color.White.copy(alpha = 0.5f)
                    )
                }
            }
        }
    }
}

@Composable
fun handlePagingResult(character: LazyPagingItems<CharacterModel>) : Boolean {
    character.apply {
        val error = when {
            loadState.refresh is LoadState.Error -> loadState.refresh as LoadState.Error
            loadState.prepend is LoadState.Error -> loadState.prepend as LoadState.Error
            loadState.append is LoadState.Error -> loadState.append as LoadState.Error
            else -> null
        }
        return when {
            loadState.refresh is LoadState.Loading -> {
                ShimmerEffect()
                false
            }
            error != null -> {
                EmptyScreen(error = error, heroes = character)
                false
            }
            character.itemCount < 1 -> {
                EmptyScreen()
                false
            }
            else -> true
        }
    }
}


