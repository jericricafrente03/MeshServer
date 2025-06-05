package com.jeric.naruto.navigation

import android.util.Log
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.outlined.Favorite
import androidx.compose.material.icons.outlined.Home
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.hilt.navigation.compose.hiltViewModel
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.navigation.NavHostController
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.paging.compose.collectAsLazyPagingItems
import com.jeric.naruto.presentation.home.HomeScreen
import com.jeric.naruto.presentation.home.HomeViewModel
import com.jeric.naruto.presentation.onboarding.OnboardingScreen
import com.jeric.naruto.presentation.onboarding.OnboardingViewModel
import com.jeric.naruto.ui.SplashScreen

@Composable
fun NavGraphSetup(
    navController: NavHostController,
) {
    NavHost(
        navController = navController,
        startDestination = Routes.Splash
    ) {
        composable<Routes.Splash> {
            val viewModel: OnboardingViewModel = hiltViewModel()
            val isCompleted by viewModel.isCompleted.collectAsStateWithLifecycle()
            SplashScreen(
                onSplashFinished = {
                    val route = if (isCompleted) Routes.HomeScreen else Routes.Onboarding
                    navController.navigate(route){
                        popUpTo(Routes.Splash) { inclusive = true }
                    }
                }
            )
        }

        composable<Routes.Onboarding> {
            OnboardingScreen(
                onFinish = {
                    navController.navigate(Routes.HomeScreen) {
                        popUpTo(Routes.Onboarding) { inclusive = true }
                    }
                }
            )
        }

        composable<Routes.HomeScreen> {
            val homeViewModel: HomeViewModel = hiltViewModel()
//            val beast by homeViewModel.tailedBeast.collectAsStateWithLifecycle()
            val items = listOf("Home" to Icons.Outlined.Home, "Favorite" to Icons.Outlined.Favorite)
            val selectedItem by remember { mutableStateOf(items[0]) }

           HomeScreen(
               navController = navController,
               snackbarEvent = homeViewModel.snackbarEvent,
             /*  character = beast,*/
               onImageClick = {},
               selectedItem = selectedItem,
               items = items
           )
        }
    }
} 