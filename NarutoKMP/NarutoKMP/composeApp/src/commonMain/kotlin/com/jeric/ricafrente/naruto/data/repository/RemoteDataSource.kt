package com.jeric.ricafrente.naruto.data.repository

import androidx.paging.PagingData
import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.model.buruto.KaraModel
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.core.model.clan.ClanModel
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.core.utils.DataState
import kotlinx.coroutines.flow.Flow

interface RemoteDataSource {
    fun getAllHeroes(): Flow<PagingData<CharacterModel>>
    fun getAllTailBeast(): Flow<DataState<List<TailedBeastModel>>>
    fun getAllClan(): Flow<DataState<List<ClanModel>>>
    fun getAkatsuki(): Flow<DataState<List<AkatsukiModel>>>
    fun getAllKara(): Flow<DataState<List<KaraModel>>>
    fun searchHeroes(query: String): Flow<PagingData<CharacterModel>>
    suspend fun getSelectedCharacter(hero: Int): CharacterModel
    suspend fun getSelectedKara(kara: Int): KaraModel
    suspend fun getSelectedAkatsuki(akatsukiModel: Int): AkatsukiModel
    suspend fun getSelectedClan(clan: Int): ClanModel
    suspend fun getSelectedTailedBeast(beast: Int): TailedBeastModel
}