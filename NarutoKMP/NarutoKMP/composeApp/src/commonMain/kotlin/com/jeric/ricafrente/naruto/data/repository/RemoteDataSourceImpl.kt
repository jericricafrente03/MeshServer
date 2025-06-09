package com.jeric.ricafrente.naruto.data.repository

import androidx.paging.Pager
import androidx.paging.PagingConfig
import androidx.paging.PagingData
import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.model.buruto.KaraModel
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.core.model.clan.ClanModel
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.core.network.helper.NarutoApi
import com.jeric.ricafrente.naruto.core.utils.Constants
import com.jeric.ricafrente.naruto.core.utils.networkBoundResource
import com.jeric.ricafrente.naruto.data.mapper.akatsuki.fromEntityToModel
import com.jeric.ricafrente.naruto.data.mapper.akatsuki.toAkatsukiModels
import com.jeric.ricafrente.naruto.data.mapper.akatsuki.toDomainModel
import com.jeric.ricafrente.naruto.data.mapper.character.toDomainModel
import com.jeric.ricafrente.naruto.data.mapper.clan.fromEntityToModel
import com.jeric.ricafrente.naruto.data.mapper.clan.toClanModels
import com.jeric.ricafrente.naruto.data.mapper.clan.toDomainModel
import com.jeric.ricafrente.naruto.data.mapper.kara.fromEntityToModel
import com.jeric.ricafrente.naruto.data.mapper.kara.toDomainModel
import com.jeric.ricafrente.naruto.data.mapper.kara.toKaraModels
import com.jeric.ricafrente.naruto.data.mapper.tailedbeast.fromEntityToModel
import com.jeric.ricafrente.naruto.data.mapper.tailedbeast.toDomainModel
import com.jeric.ricafrente.naruto.data.mapper.tailedbeast.toTailedBeastModels
import com.jeric.ricafrente.naruto.data.paging_source.SearchCharacterPagingSource
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.IO
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.flowOn
import kotlinx.coroutines.flow.map


class RemoteDataSourceImpl(
    private val local: LocalDataSourcesInterface,
    private val client: NarutoApi
) : RemoteDataSource {

    override fun getAllHeroes(): Flow<PagingData<CharacterModel>> =
        Pager(PagingConfig(pageSize = Constants.SIZE)) {
            local.characterDao().getAllCharacters(Constants.SIZE)
        }.flow.flowOn(Dispatchers.IO)

    override fun getAllTailBeast() = networkBoundResource(
        query = { local.tailBeastDao().getAllTailedBeastFlow().map { it.fromEntityToModel() } },
        fetch = { client.getAllTailBeasts() },
        saveFetchResult = { response ->
            local.tailBeastDao().deleteAllTailedBeast()
            response.tailedBeasts.toTailedBeastModels().forEach {
                local.tailBeastDao().insert(it)
            }
        }
    )

    override fun getAllClan() = networkBoundResource(
        query = { local.clanDao().getAllClan().map { it.fromEntityToModel() } },
        fetch = { client.getClan() },
        saveFetchResult = { response ->
            local.clanDao().deleteClan()
            response.clans.toClanModels().forEach {
                local.clanDao().insertClan(it)
            }
        }
    )

    override fun getAkatsuki() = networkBoundResource(
        query = { local.akatsukiDao().getAllAkatsuki().map { it.fromEntityToModel() } },
        fetch = { client.getAkatsuki() },
        saveFetchResult = { response ->
            local.akatsukiDao().deleteAkatsuki()
            response.akatsuki.toAkatsukiModels().forEach {
                local.akatsukiDao().insertAkatsuki(it)
            }
        }
    )


    override fun getAllKara() = networkBoundResource(
        query = { local.karaDao().getAllKara().map { it.fromEntityToModel() } },
        fetch = { client.getBoruto() },
        saveFetchResult = { response ->
            local.karaDao().deleteKara()
            response.kara.toKaraModels().forEach {
                local.karaDao().insertKara(it)
            }
        }
    )

    override fun searchHeroes(query: String): Flow<PagingData<CharacterModel>> {
        return Pager(
            config = PagingConfig(pageSize = Constants.SIZE),
            pagingSourceFactory = {
                SearchCharacterPagingSource(narutoClient = client, query = query)
            }
        ).flow.flowOn(Dispatchers.IO)
    }

    override suspend fun getSelectedCharacter(hero: Int): CharacterModel {
        val apiResponse = client.getCharacterById(hero.toString())
        return apiResponse.toDomainModel()
    }

    override suspend fun getSelectedKara(kara: Int): KaraModel {
        return local.karaDao().selectOneById(kara.toString()).toDomainModel()
    }

    override suspend fun getSelectedClan(clan: Int): ClanModel {
        return local.clanDao().selectOneById(clan.toString()).toDomainModel()
    }

    override suspend fun getSelectedTailedBeast(beast: Int): TailedBeastModel {
        return local.tailBeastDao().selectOneById(beast.toString()).toDomainModel()
    }

    override suspend fun getSelectedAkatsuki(akatsukiModel: Int): AkatsukiModel {
        return local.akatsukiDao().selectOneById(akatsukiModel.toString()).toDomainModel()
    }

}
