package com.jeric.ricafrente.naruto.ui.navigation

import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.navigation.NavHostController
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.toRoute
import com.jeric.ricafrente.naruto.ui.akatsuki.AkatsukiScreen
import com.jeric.ricafrente.naruto.ui.akatsuki.AkatsukiViewModel
import com.jeric.ricafrente.naruto.ui.akatsuki.character.AkatsukiCharacterScreen
import com.jeric.ricafrente.naruto.ui.akatsuki.character.AkatsukiCharacterStateEvent
import com.jeric.ricafrente.naruto.ui.akatsuki.character.AkatsukiCharacterViewModel
import com.jeric.ricafrente.naruto.ui.buruto.KaraScreen
import com.jeric.ricafrente.naruto.ui.buruto.KaraViewModel
import com.jeric.ricafrente.naruto.ui.buruto.character.KaraSelectedScreen
import com.jeric.ricafrente.naruto.ui.buruto.character.KaraSelectedStateEvent
import com.jeric.ricafrente.naruto.ui.buruto.character.KaraSelectedViewModel
import com.jeric.ricafrente.naruto.ui.character.CharacterScreen
import com.jeric.ricafrente.naruto.ui.character.CharacterViewModel
import com.jeric.ricafrente.naruto.ui.character.details.GetDetailsCharacterScreen
import com.jeric.ricafrente.naruto.ui.character.details.GetSelectedCharacterStateEvent
import com.jeric.ricafrente.naruto.ui.character.details.GetSelectedCharacterViewModel
import com.jeric.ricafrente.naruto.ui.clan.ClanScreen
import com.jeric.ricafrente.naruto.ui.clan.ClanViewModel
import com.jeric.ricafrente.naruto.ui.details_screen.GetDetailsScreen
import com.jeric.ricafrente.naruto.ui.details_screen.GetSelectedStateEvent
import com.jeric.ricafrente.naruto.ui.details_screen.GetSelectedViewModel
import com.jeric.ricafrente.naruto.ui.home_page.HomeScreen
import com.jeric.ricafrente.naruto.ui.home_page.HomeViewModel
import com.jeric.ricafrente.naruto.ui.onboarding.OnboardingScreen
import com.jeric.ricafrente.naruto.ui.onboarding.OnboardingViewModel
import com.jeric.ricafrente.naruto.ui.search.SearchScreen
import com.jeric.ricafrente.naruto.ui.search.SearchViewModel
import com.jeric.ricafrente.naruto.ui.splash_screen.SplashScreen
import com.jeric.ricafrente.naruto.ui.splash_screen.SplashViewModel
import com.jeric.ricafrente.naruto.ui.tailed_beast.GetSelectedTailedBeastStateEvent
import com.jeric.ricafrente.naruto.ui.tailed_beast.GetSelectedTailedBeastViewModel
import com.jeric.ricafrente.naruto.ui.tailed_beast.TailedBeastScreen
import org.koin.compose.viewmodel.koinViewModel

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun NavGraphSetup(
    navController: NavHostController,
) {
    NavHost(
        navController = navController,
        startDestination = Routes.Splash
    ) {
        composable<Routes.Splash> {
            val viewModel = koinViewModel<SplashViewModel>()
            val isCompleted by viewModel.isCompleted.collectAsStateWithLifecycle()
            SplashScreen(
                onSplashFinished = {
                    val route = if (isCompleted) Routes.HomeScreen else Routes.Onboarding
                    navController.navigate(route) {
                        popUpTo(Routes.Splash) { inclusive = true }
                    }
                }
            )
        }

        composable<Routes.Onboarding> {
            val viewModel = koinViewModel<OnboardingViewModel>()
            OnboardingScreen(
                viewModel = viewModel,
                navHostController = navController,
            )
        }

        composable<Routes.HomeScreen> {
            val viewModel = koinViewModel<HomeViewModel>()
            HomeScreen(
                viewModel = viewModel,
                navHostController = navController,
            )
        }

        composable<Routes.SearchScreen> {
            val viewModel = koinViewModel<SearchViewModel>()
            SearchScreen(
                navController = navController,
                viewModel = viewModel,
            )
        }

        composable<Routes.SearchSelected> {
            val viewModel = koinViewModel<GetSelectedViewModel>()
            val detailsId = it.toRoute<Routes.SearchSelected>().id
            LaunchedEffect(key1 = detailsId){
                detailsId.let {
                    viewModel.onEvent(GetSelectedStateEvent.Event.GotoDetailsEvent(detailsId.toInt()))
                }
            }
            GetDetailsScreen(
                viewModel = viewModel,
                navHostController = navController
            )
        }
        composable<Routes.TailedBeast> {
            val viewModel = koinViewModel<GetSelectedTailedBeastViewModel>()
            val detailsId = it.toRoute<Routes.TailedBeast>().tailBeast
            LaunchedEffect(key1 = detailsId){
                detailsId.let {
                    viewModel.onEvent(GetSelectedTailedBeastStateEvent.Event.GotoDetailsEvent(detailsId.toInt()))
                }
            }
            TailedBeastScreen(
                viewModel = viewModel,
                navHostController = navController
            )
        }
        composable<Routes.Clan> {
            val viewModel = koinViewModel<ClanViewModel>()
            ClanScreen(
                viewModel = viewModel,
                navHostController = navController
            )
        }
        composable<Routes.Character> {
            val viewModel = koinViewModel<CharacterViewModel>()
            CharacterScreen(
                viewModel = viewModel,
                navHostController = navController
            )
        }

        composable<Routes.CharacterFullScreen> {
            val viewModel = koinViewModel<GetSelectedCharacterViewModel>()
            val detailsId = it.toRoute<Routes.CharacterFullScreen>().image
            LaunchedEffect(key1 = detailsId){
                detailsId.let {
                    viewModel.onEvent(GetSelectedCharacterStateEvent.Event.GotoDetailsEvent(detailsId.toInt()))
                }
            }
            GetDetailsCharacterScreen(
                viewModel = viewModel,
                navHostController = navController
            )
        }
        composable<Routes.Kara> {
            val viewModel = koinViewModel<KaraViewModel>()
            KaraScreen(
                viewModel = viewModel,
                navHostController = navController
            )
        }

        composable<Routes.KaraCharacter> {
            val viewModel = koinViewModel<KaraSelectedViewModel>()
            val detailsId = it.toRoute<Routes.KaraCharacter>().id
            LaunchedEffect(key1 = detailsId){
                detailsId.let {
                    viewModel.onEvent(KaraSelectedStateEvent.Event.GotoDetailsEvent(detailsId))
                }
            }
            KaraSelectedScreen(
                viewModel = viewModel,
                navHostController = navController
            )
        }
        composable<Routes.TeamAkatsuki> {
            val viewModel = koinViewModel<AkatsukiViewModel>()
            AkatsukiScreen(
                viewModel = viewModel,
                navHostController = navController
            )
        }
        composable<Routes.AkatsukiCharacter> {
            val viewModel = koinViewModel<AkatsukiCharacterViewModel>()
            val detailsId = it.toRoute<Routes.KaraCharacter>().id
            LaunchedEffect(key1 = detailsId){
                detailsId.let {
                    viewModel.onEvent(AkatsukiCharacterStateEvent.Event.GotoDetailsEvent(detailsId))
                }
            }
            AkatsukiCharacterScreen(
                viewModel = viewModel,
                navHostController = navController
            )
        }
    }
} 