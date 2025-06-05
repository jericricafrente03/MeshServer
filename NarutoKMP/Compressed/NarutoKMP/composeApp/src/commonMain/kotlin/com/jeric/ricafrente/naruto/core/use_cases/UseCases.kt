package com.jeric.ricafrente.naruto.core.use_cases

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

data class UseCases(
    val getAllCharacterUseCase: GetAllCharacterUseCase,
    val getAllTailBeastUseCase: GetAllTailBeastUseCase,
    val getAllClanUseCase: GetAllClanUseCase,
    val getAllAkatsukiUseCase: GetAllAkatsukiUseCase,
    val getAllKaraUseCase: GetAllKaraUseCase,
    val searchHeroesUseCase: SearchHeroesUseCase,
    val getSelectedHeroesUseCase: GetSelectedHeroesUseCase,
    val getSelectedKaraUseCase: GetSelectedKaraUseCase,
    val getSelectedAkatsukiUseCase: GetSelectedAkatsukiUseCase,
    val getSelectedTailedBeastUseCase: GetSelectedTailedBeastUseCase,
)