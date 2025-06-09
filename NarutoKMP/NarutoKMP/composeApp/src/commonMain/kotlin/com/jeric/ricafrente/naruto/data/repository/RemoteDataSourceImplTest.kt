package com.jeric.ricafrente.naruto.data.repository

import androidx.paging.PagingData
import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.model.buruto.KaraModel
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.core.model.clan.ClanModel
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.core.utils.DataState
import kotlinx.coroutines.flow.Flow


class RemoteDataSourceImplTest(
    private val remote: RemoteDataSource
) {
    fun getAllCharacter(): Flow<PagingData<CharacterModel>> {
        return remote.getAllHeroes()
    }
    fun getAllTailBeast(): Flow<DataState<List<TailedBeastModel>>> {
        return remote.getAllTailBeast()
    }
    fun getClan(): Flow<DataState<List<ClanModel>>> {
        return remote.getAllClan()
    }
    fun getAkatsuki(): Flow<DataState<List<AkatsukiModel>>> {
        return remote.getAkatsuki()
    }
    fun getKara(): Flow<DataState<List<KaraModel>>> {
        return remote.getAllKara()
    }

    fun searchHeroes(query: String): Flow<PagingData<CharacterModel>> {
        return remote.searchHeroes(query = query)
    }

    suspend fun getSelectedCharacter(clan: Int): CharacterModel {
        return remote.getSelectedCharacter(hero = clan)
    }

    suspend fun getSelectedKara(kara: Int): KaraModel {
        return remote.getSelectedKara(kara = kara)
    }

    suspend fun getSelectedAkatsuki(akatsukiModel: Int): AkatsukiModel {
        return remote.getSelectedAkatsuki(akatsukiModel = akatsukiModel)
    }

    suspend fun getSelectedClan(clan: Int): ClanModel{
        return remote.getSelectedClan(clan = clan)
    }

    suspend fun getSelectedTailedBeast(beast: Int): TailedBeastModel{
        return remote.getSelectedTailedBeast(beast = beast)
    }

}
