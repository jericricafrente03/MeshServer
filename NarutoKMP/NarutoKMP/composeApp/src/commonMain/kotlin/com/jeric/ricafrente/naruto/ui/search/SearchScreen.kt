package com.jeric.ricafrente.naruto.ui.search

import androidx.compose.foundation.layout.WindowInsets
import androidx.compose.foundation.layout.statusBars
import androidx.compose.foundation.layout.windowInsetsPadding
import androidx.compose.material3.Scaffold
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.ui.Modifier
import androidx.lifecycle.compose.LocalLifecycleOwner
import androidx.lifecycle.flowWithLifecycle
import androidx.navigation.NavHostController
import app.cash.paging.compose.collectAsLazyPagingItems
import com.jeric.ricafrente.naruto.ui.navigation.Routes
import kotlinx.coroutines.flow.collectLatest


@Composable
fun SearchScreen(
    navController: NavHostController,
    viewModel: SearchViewModel,
) {
    val searchQuery by viewModel.searchQuery

    val character = viewModel.searchedHeroes.collectAsLazyPagingItems()
    val lifecycleOwner = LocalLifecycleOwner.current

    LaunchedEffect(key1 = viewModel.navigation) {
        viewModel.navigation.flowWithLifecycle(lifecycleOwner.lifecycle)
            .collectLatest { navigation ->
                when (navigation) {
                    SearchOnlineStateEvent.Navigation.BackNav -> {
                        navController.popBackStack()
                    }
                    is SearchOnlineStateEvent.Navigation.GotoDetailNav -> {
                        navController.navigate(Routes.SearchSelected(navigation.id))
                    }
                }
            }
    }

    Scaffold(
        topBar = {
            SearchTopBar(
                text = searchQuery,
                onTextChange = {
                    viewModel.updateSearchQuery(query = it)
                },
                onSearchClicked = {
                    viewModel.searchCharacter(query = it)
                },
                onCloseClicked = {
                   viewModel.onEvent(SearchOnlineStateEvent.Event.GotoHome)
                },
            )
        },
        content = { padding ->
            ListContent(padding = padding, heroes = character, onClick = {
                viewModel.onEvent(SearchOnlineStateEvent.Event.GotoDetailEvent(it))
            })
        },
        modifier = Modifier.windowInsetsPadding(WindowInsets.statusBars)
    )
}
