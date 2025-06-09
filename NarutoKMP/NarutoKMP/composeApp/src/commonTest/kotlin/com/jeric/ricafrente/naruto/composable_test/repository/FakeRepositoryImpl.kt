package com.jeric.ricafrente.naruto.composable_test.repository

import androidx.paging.PagingData
import com.jeric.ricafrente.naruto.composable_test.utils.akatsukiList
import com.jeric.ricafrente.naruto.composable_test.utils.characterList
import com.jeric.ricafrente.naruto.composable_test.utils.clanList
import com.jeric.ricafrente.naruto.composable_test.utils.getTailedBeast
import com.jeric.ricafrente.naruto.composable_test.utils.karaList
import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.model.buruto.KaraModel
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.core.model.clan.ClanModel
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.core.utils.DataState
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.flow
import kotlinx.coroutines.flow.flowOf

class FakeRepositoryImpl : RemoteDataSource {

    override fun getAllHeroes(): Flow<PagingData<CharacterModel>> {
        val characters = characterList()
        return flowOf(PagingData.from(characters))
    }

    override fun getAllTailBeast() = flow {
        emit(DataState.Loading())
        emit(DataState.Success(getTailedBeast()))
    }

    override fun getAllClan() = flow {
        emit(DataState.Loading())
        emit(DataState.Success(clanList()))
    }

    override fun getAkatsuki() = flow {
        emit(DataState.Loading())
        emit(DataState.Success(akatsukiList()))
    }

    override fun searchHeroes(query: String): Flow<PagingData<CharacterModel>> {
        TODO("Not yet implemented")
    }

    override fun getAllKara() = flow {
        emit(DataState.Loading())
        emit(DataState.Success(karaList()))
    }

    override suspend fun getSelectedAkatsuki(akatsukiModel: Int): AkatsukiModel {
        return akatsukiList().find { it.id.toInt() == akatsukiModel } ?: akatsukiList().first()
    }

    override suspend fun getSelectedClan(clan: Int): ClanModel {
        return clanList().find { it.id.toInt() == clan } ?: clanList().first()
    }


    override suspend fun getSelectedTailedBeast(beast: Int): TailedBeastModel {
        return getTailedBeast().find { it.id == beast } ?: getTailedBeast().first()
    }

    override suspend fun getSelectedCharacter(hero: Int): CharacterModel {
        return characterList().find { it.id.toInt() == hero } ?: characterList().first()
    }

    override suspend fun getSelectedKara(kara: Int): KaraModel {
        return karaList().find { it.id.toInt() == kara } ?: karaList().first()
    }


}