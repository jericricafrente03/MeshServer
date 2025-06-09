@file:OptIn(ExperimentalMaterial3Api::class)

package com.jeric.ricafrente.naruto.ui.home_page

import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.outlined.Favorite
import androidx.compose.material.icons.outlined.Home
import androidx.compose.material.icons.rounded.Menu
import androidx.compose.material3.DrawerValue
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.HorizontalDivider
import androidx.compose.material3.Icon
import androidx.compose.material3.IconButton
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.ModalNavigationDrawer
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.material3.TopAppBar
import androidx.compose.material3.TopAppBarDefaults
import androidx.compose.material3.rememberDrawerState
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.rememberCoroutineScope
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.testTag
import androidx.compose.ui.text.font.FontFamily
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.compose.LocalLifecycleOwner
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.lifecycle.flowWithLifecycle
import androidx.navigation.NavHostController
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.ui.helper.LocalSafeArea
import com.jeric.ricafrente.naruto.ui.helper.NarutoErrorMessage
import com.jeric.ricafrente.naruto.ui.helper.NarutoLoadingBar
import com.jeric.ricafrente.naruto.ui.home_page.components.CategoryButton
import com.jeric.ricafrente.naruto.ui.home_page.components.MainModalDrawerSheet
import com.jeric.ricafrente.naruto.ui.home_page.components.SearchBarButton
import com.jeric.ricafrente.naruto.ui.home_page.utils.CategoryState
import com.jeric.ricafrente.naruto.ui.navigation.Routes
import kotlinx.coroutines.flow.collectLatest
import kotlinx.coroutines.launch
import narutokmp.composeapp.generated.resources.Res
import narutokmp.composeapp.generated.resources.golos_regular
import org.jetbrains.compose.resources.Font


@Composable
fun HomeScreen(
    viewModel: HomeViewModel,
    navHostController: NavHostController,
) {
    val tailedBeastModel by viewModel.uiState.collectAsStateWithLifecycle()

    val drawerState = rememberDrawerState(DrawerValue.Closed)
    val items = listOf("Home" to Icons.Outlined.Home, "Favorite" to Icons.Outlined.Favorite)
    val selectedItem by remember { mutableStateOf(items[0]) }
    val scope = rememberCoroutineScope()
    val lifecycleOwner = LocalLifecycleOwner.current

    LaunchedEffect(key1 = viewModel.navigation) {
        viewModel.navigation.flowWithLifecycle(lifecycleOwner.lifecycle)
            .collectLatest { navigation ->
                when (navigation) {
                    HomeStateEvent.Navigation.GotoAkatsukiNav -> navHostController.navigate(Routes.TeamAkatsuki)
                    HomeStateEvent.Navigation.GotoCharacterNav -> navHostController.navigate(Routes.Character)
                    HomeStateEvent.Navigation.GotoClanNav -> navHostController.navigate(Routes.Clan)
                    HomeStateEvent.Navigation.GotoKaraNav -> navHostController.navigate(Routes.Kara)
                    HomeStateEvent.Navigation.GotoSearchNav -> navHostController.navigate(Routes.SearchScreen)
                    is HomeStateEvent.Navigation.GotoTailedBeastDetailNav -> navHostController.navigate(
                        Routes.TailedBeast(navigation.id)
                    )
                }
            }
    }

    ModalNavigationDrawer(
        drawerState = drawerState,
        drawerContent = {
            MainModalDrawerSheet(
                items = items,
                selectedItem = selectedItem,
                onItemsClick = {
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
                HomeContent(
                    uiState = tailedBeastModel,
                    event = viewModel::onEvent,
                    modifier = Modifier.padding(paddingValues)
                )
            }
        }
    )
}

@Composable
fun HomeContent(
    uiState: HomeStateEvent.UiState,
    event: (HomeStateEvent.Event) -> Unit,
    modifier: Modifier = Modifier
) {
    when {
        uiState.isLoading -> NarutoLoadingBar()
        uiState.error != null -> NarutoErrorMessage(message = uiState.error.toString())
        else -> {
            HomeDetails(
                uiState = uiState.isSuccess,
                event = event,
                modifier = modifier
            )
        }
    }
}


@Composable
fun HomeDetails(
    uiState: List<TailedBeastModel>,
    event: (HomeStateEvent.Event) -> Unit,
    modifier: Modifier = Modifier
) {
    Column(modifier = modifier.verticalScroll(rememberScrollState())) {
        Text(
            text = "Which Naruto character are you looking for?",
            color = MaterialTheme.colorScheme.onBackground,
            style = MaterialTheme.typography.headlineLarge.copy(
                fontWeight = FontWeight.Medium,
            ),
            modifier = Modifier.padding(horizontal = 15.dp, vertical = 15.dp)
        )
        SearchBarButton(containerColor = Color.Gray.copy(alpha = .15f)) {
            event(HomeStateEvent.Event.GotoSearchEvent)
        }
        Row(
            horizontalArrangement = Arrangement.spacedBy(20.dp),
            modifier = Modifier.padding(horizontal = 20.dp, vertical = 10.dp)
        ) {
            CategoryButton(
                onClick = { event(HomeStateEvent.Event.GotoClanEvent) },
                categoryState = CategoryState.clan,
                modifier = Modifier.weight(1f)
                    .testTag("button_goto_clan")
            )
            CategoryButton(
                onClick = { event(HomeStateEvent.Event.GotoCharacterEvent) },
                categoryState = CategoryState.character,
                modifier = Modifier.weight(1f)
                    .testTag("button_goto_character")
            )
        }
        Row(
            horizontalArrangement = Arrangement.spacedBy(20.dp),
            modifier = Modifier.padding(horizontal = 20.dp, vertical = 10.dp)
        ) {
            CategoryButton(
                onClick = { event(HomeStateEvent.Event.GotoKaraEvent) },
                categoryState = CategoryState.villages,
                modifier = Modifier.weight(1f)
                    .testTag("button_goto_kara")
            )
            CategoryButton(
                onClick = { event(HomeStateEvent.Event.GotoAkatsukiEvent) },
                categoryState = CategoryState.akatsuki,
                modifier = Modifier.weight(1f)
                    .testTag("button_goto_akatsuki")
            )
        }
        Text(
            text = "Tailed Beast",
            color = MaterialTheme.colorScheme.onBackground,
            style = MaterialTheme.typography.titleMedium.copy(
                fontWeight = FontWeight.Medium,
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

        TailedBeastList(
            itemList = uiState,
            onClickItem = {
                event(HomeStateEvent.Event.GotoTailedBeastDetailEvent(it))
            }
        )
    }

}