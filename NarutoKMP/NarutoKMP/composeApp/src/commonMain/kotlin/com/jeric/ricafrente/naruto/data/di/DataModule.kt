package com.jeric.ricafrente.naruto.data.di


import com.jeric.ricafrente.naruto.core.use_cases.UseCases
import com.jeric.ricafrente.naruto.core.use_cases.get_all_akatsuki.GetAllAkatsukiUseCase
import com.jeric.ricafrente.naruto.core.use_cases.get_all_akatsuki.GetSelectedAkatsukiUseCase
import com.jeric.ricafrente.naruto.core.use_cases.get_all_character.GetAllCharacterUseCase
import com.jeric.ricafrente.naruto.core.use_cases.get_all_clan.GetAllClanUseCase
import com.jeric.ricafrente.naruto.core.use_cases.get_all_kara.GetAllKaraUseCase
import com.jeric.ricafrente.naruto.core.use_cases.get_all_kara.GetSelectedKaraUseCase
import com.jeric.ricafrente.naruto.core.use_cases.get_all_tailbeast.GetAllTailBeastUseCase
import com.jeric.ricafrente.naruto.core.use_cases.get_all_tailbeast.GetSelectedTailedBeastUseCase
import com.jeric.ricafrente.naruto.core.use_cases.search_character.GetSelectedHeroesUseCase
import com.jeric.ricafrente.naruto.core.use_cases.search_character.SearchHeroesUseCase
import com.jeric.ricafrente.naruto.data.repository.LocalDataSources
import com.jeric.ricafrente.naruto.data.repository.PreferenceManager
import com.jeric.ricafrente.naruto.data.repository.PreferencesManagerImpl
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSourceImpl
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSourceImplTest
import com.jeric.ricafrente.naruto.ui.akatsuki.AkatsukiViewModel
import com.jeric.ricafrente.naruto.ui.akatsuki.character.AkatsukiCharacterViewModel
import com.jeric.ricafrente.naruto.ui.buruto.KaraViewModel
import com.jeric.ricafrente.naruto.ui.buruto.character.KaraSelectedViewModel
import com.jeric.ricafrente.naruto.ui.character.CharacterViewModel
import com.jeric.ricafrente.naruto.ui.character.details.GetSelectedCharacterViewModel
import com.jeric.ricafrente.naruto.ui.clan.ClanViewModel
import com.jeric.ricafrente.naruto.ui.details_screen.GetSelectedViewModel
import com.jeric.ricafrente.naruto.ui.home_page.HomeViewModel
import com.jeric.ricafrente.naruto.ui.onboarding.OnboardingViewModel
import com.jeric.ricafrente.naruto.ui.search.SearchViewModel
import com.jeric.ricafrente.naruto.ui.splash_screen.SplashViewModel
import com.jeric.ricafrente.naruto.ui.tailed_beast.GetSelectedTailedBeastViewModel
import org.koin.core.module.dsl.viewModel
import org.koin.dsl.module


val dataModule = module {
    single{ LocalDataSources(get(), get(), get(), get(), get()) }
    single<RemoteDataSource> { RemoteDataSourceImpl(get(), get()) }
    single{ RemoteDataSourceImplTest(get()) }
    single{ GetAllCharacterUseCase(get()) }
    single{ GetAllTailBeastUseCase(get()) }
    single{ GetAllAkatsukiUseCase(get()) }
    single{ GetAllClanUseCase(get()) }
    single{ GetAllKaraUseCase(get()) }
    single{ SearchHeroesUseCase(get()) }
    single{ GetSelectedHeroesUseCase(get()) }
    single{ GetSelectedTailedBeastUseCase(get()) }
    single{ GetSelectedKaraUseCase(get()) }
    single{ GetSelectedAkatsukiUseCase(get()) }
    single{ UseCases(get(), get(), get(), get(), get(), get(), get(), get(), get(), get() ) }
    single<PreferenceManager> { PreferencesManagerImpl(get()) }
    viewModel { HomeViewModel(get()) }
    viewModel { SplashViewModel(get()) }
    viewModel { SearchViewModel(get()) }
    viewModel { GetSelectedViewModel(get()) }
    viewModel { GetSelectedTailedBeastViewModel(get()) }
    viewModel { ClanViewModel(get()) }
    viewModel { CharacterViewModel(get()) }
    viewModel { GetSelectedCharacterViewModel(get()) }
    viewModel { KaraViewModel(get()) }
    viewModel { KaraSelectedViewModel(get()) }
    viewModel { AkatsukiViewModel(get()) }
    viewModel { AkatsukiCharacterViewModel(get()) }
    viewModel { OnboardingViewModel(get()) }
}