package com.jeric.ricafrente.naruto.composable_test

import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.ui.test.ComposeUiTest
import androidx.compose.ui.test.ExperimentalTestApi
import androidx.compose.ui.test.assert
import androidx.compose.ui.test.assertIsDisplayed
import androidx.compose.ui.test.hasTestTag
import androidx.compose.ui.test.onAllNodesWithTag
import androidx.compose.ui.test.onChildAt
import androidx.compose.ui.test.onNodeWithTag
import androidx.compose.ui.test.performClick
import androidx.compose.ui.test.runComposeUiTest
import androidx.navigation.compose.rememberNavController
import com.jeric.ricafrente.naruto.composable_test.repository.FakeRepositoryImpl
import com.jeric.ricafrente.naruto.composable_test.utils.akatsukiList
import com.jeric.ricafrente.naruto.composable_test.utils.characterList
import com.jeric.ricafrente.naruto.composable_test.utils.clanList
import com.jeric.ricafrente.naruto.composable_test.utils.getTailedBeast
import com.jeric.ricafrente.naruto.composable_test.utils.karaList
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
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSourceImplTest
import com.jeric.ricafrente.naruto.ui.akatsuki.AkatsukiScreen
import com.jeric.ricafrente.naruto.ui.akatsuki.AkatsukiViewModel
import com.jeric.ricafrente.naruto.ui.buruto.KaraScreen
import com.jeric.ricafrente.naruto.ui.buruto.KaraViewModel
import com.jeric.ricafrente.naruto.ui.character.CharacterScreen
import com.jeric.ricafrente.naruto.ui.character.CharacterViewModel
import com.jeric.ricafrente.naruto.ui.clan.ClanScreen
import com.jeric.ricafrente.naruto.ui.clan.ClanViewModel
import com.jeric.ricafrente.naruto.ui.home_page.HomeDetails
import com.jeric.ricafrente.naruto.ui.tailed_beast.GetSelectedTailedBeastViewModel
import com.jeric.ricafrente.naruto.ui.tailed_beast.TailedBeastScreen
import kotlin.test.BeforeTest
import kotlin.test.Test

class ComposeAppCommonTest {
    private lateinit var getAllAkatsukiUseCase: GetAllAkatsukiUseCase
    private lateinit var getSelectedAkatsukiUseCase: GetSelectedAkatsukiUseCase
    private lateinit var getAllClanUseCase: GetAllClanUseCase
    private lateinit var getAllCharacterUseCase: GetAllCharacterUseCase
    private lateinit var getAllKaraUseCase: GetAllKaraUseCase
    private lateinit var getSelectedKaraUseCase: GetSelectedKaraUseCase
    private lateinit var getAllTailBeastUseCase: GetAllTailBeastUseCase
    private lateinit var getSelectedTailedBeastUseCase: GetSelectedTailedBeastUseCase
    private lateinit var getSearchHeroesUseCase: SearchHeroesUseCase
    private lateinit var getSelectedHeroesUseCase: GetSelectedHeroesUseCase

    private lateinit var getAllUseCases: UseCases

    private lateinit var remoteDataSource: FakeRepositoryImpl
    private lateinit var fakeSuccessRepo: RemoteDataSourceImplTest

    @OptIn(ExperimentalTestApi::class)
    fun ComposeUiTest.waitForUIToSettle(tag: String, timeout: Long = 2000) {
        waitUntil(timeoutMillis = timeout) {
            onAllNodesWithTag(tag).fetchSemanticsNodes().isNotEmpty()
        }
    }

    @BeforeTest
    fun setUp() {
        remoteDataSource = FakeRepositoryImpl()
        fakeSuccessRepo = RemoteDataSourceImplTest(
            remote = remoteDataSource
        )
        getAllKaraUseCase = GetAllKaraUseCase(repository = fakeSuccessRepo)
        getAllClanUseCase = GetAllClanUseCase(repository = fakeSuccessRepo)
        getAllAkatsukiUseCase = GetAllAkatsukiUseCase(repository = fakeSuccessRepo)
        getSelectedKaraUseCase = GetSelectedKaraUseCase(repository = fakeSuccessRepo)
        getAllTailBeastUseCase = GetAllTailBeastUseCase(repository = fakeSuccessRepo)
        getAllCharacterUseCase = GetAllCharacterUseCase(repository = fakeSuccessRepo)
        getSelectedAkatsukiUseCase = GetSelectedAkatsukiUseCase(repository = fakeSuccessRepo)
        getSearchHeroesUseCase = SearchHeroesUseCase(repository = fakeSuccessRepo)
        getSelectedHeroesUseCase = GetSelectedHeroesUseCase(repository = fakeSuccessRepo)
        getSelectedTailedBeastUseCase = GetSelectedTailedBeastUseCase(repository = fakeSuccessRepo)


        getAllUseCases = UseCases(
            getAllKaraUseCase = getAllKaraUseCase,
            getAllClanUseCase = getAllClanUseCase,
            getAllAkatsukiUseCase = getAllAkatsukiUseCase,
            getSelectedKaraUseCase = getSelectedKaraUseCase,
            getAllTailBeastUseCase = getAllTailBeastUseCase,
            getAllCharacterUseCase = getAllCharacterUseCase,
            getSelectedAkatsukiUseCase = getSelectedAkatsukiUseCase,
            searchHeroesUseCase = getSearchHeroesUseCase,
            getSelectedHeroesUseCase = getSelectedHeroesUseCase,
            getSelectedTailedBeastUseCase = getSelectedTailedBeastUseCase
        )
    }


    @OptIn(ExperimentalTestApi::class)
    @Test
    fun testHomeDetails() = runComposeUiTest {
        setContent {
            HomeDetails(
                uiState = getTailedBeast(),
                event = {}
            )

        }
        onNodeWithTag("button_goto_clan").performClick()
        onNodeWithTag("button_goto_character").performClick()
        onNodeWithTag("button_goto_kara").performClick()
        onNodeWithTag("button_goto_akatsuki").performClick()
        onNodeWithTag("tailedBeastTag").assertIsDisplayed()
        onNodeWithTag("tailedBeastTag")
            .onChildAt(0).assert(hasTestTag(getTailedBeast().first().id.toString()))

    }


    @OptIn(ExperimentalTestApi::class)
    @Test
    fun testKaraScreen() = runComposeUiTest {
        val karaViewModel = KaraViewModel(useCases = getAllUseCases)
        setContent {
            KaraScreen(
                viewModel = karaViewModel,
                navHostController = rememberNavController()
            )
        }

        onNodeWithTag("kara_back").performClick()
        onNodeWithTag("kara_col").assertIsDisplayed()
        onNodeWithTag("kara_col")
            .onChildAt(0).assert(hasTestTag(karaList().first().id))

    }

    @OptIn(ExperimentalTestApi::class)
    @Test
    fun testAkatsukiScreen() = runComposeUiTest {
        val karaViewModel = AkatsukiViewModel(useCases = getAllUseCases)
        setContent {
            AkatsukiScreen(
                viewModel = karaViewModel,
                navHostController = rememberNavController()
            )
        }
        onNodeWithTag("akatsuki_back").performClick()
        onNodeWithTag("akatsuki_col").assertIsDisplayed()
        onNodeWithTag("akatsuki_col")
            .onChildAt(0).assert(hasTestTag(akatsukiList().first().id))

    }

    @OptIn(ExperimentalTestApi::class)
    @Test
    fun testClanScreen() = runComposeUiTest {
        val clanViewModel = ClanViewModel(useCases = getAllUseCases)
        setContent {
            ClanScreen(
                viewModel = clanViewModel,
                navHostController = rememberNavController()
            )
        }
        onNodeWithTag("clan_back").performClick()
        onNodeWithTag("clan_col").assertIsDisplayed()
        onNodeWithTag("clan_col")
            .onChildAt(0).assert(hasTestTag(clanList().first().id))

    }

    @OptIn(ExperimentalTestApi::class)
    @Test
    fun testCharacterScreen() = runComposeUiTest {
        val homeViewModel = CharacterViewModel(useCases = getAllUseCases)
        setContent {
            CharacterScreen(
                viewModel = homeViewModel,
                navHostController = rememberNavController()
            )
        }
        onNodeWithTag("character_back").performClick()
        onNodeWithTag("character_col").assertIsDisplayed()
        onNodeWithTag("character_col")
            .onChildAt(0).assert(hasTestTag(characterList().first().id))

    }




}