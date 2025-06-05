package com.jeric.naruto.data.repository

import androidx.paging.ExperimentalPagingApi
import androidx.paging.Pager
import androidx.paging.PagingConfig
import androidx.paging.PagingData
import androidx.paging.map
import com.jeric.naruto.data.local.NarutoDatabase
import com.jeric.naruto.data.mapper.character.characterEntityToDomain
import com.jeric.naruto.data.mapper.tailbeast.tailBeastEntityToDomain
import com.jeric.naruto.data.mapper.tailbeast.toEntityList
import com.jeric.naruto.data.mapper.tailbeast.toModelList
import com.jeric.naruto.data.paging_source.CharacterRemoteMediator
import com.jeric.naruto.data.remote.NarutoApi
import com.jeric.naruto.domain.model.character.CharacterModel
import com.jeric.naruto.domain.model.tail_beast.TailedBeastModel
import com.jeric.naruto.domain.repository.RemoteDataSource
import com.jeric.naruto.util.Constants
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.flow
import kotlinx.coroutines.flow.map

@ExperimentalPagingApi
class RemoteDataSourceImpl(
    private val narutoApi: NarutoApi,
    private val database: NarutoDatabase
) : RemoteDataSource {

    private val heroDao = database.heroDao()
//    private val tailBeastDao = database.tailBeastDao()

    override fun getAllHeroes(): Flow<PagingData<CharacterModel>> {
        return Pager(
            config = PagingConfig(pageSize = Constants.SIZE),
            remoteMediator = CharacterRemoteMediator(narutoApi, database),
            pagingSourceFactory = { heroDao.getAllHeroes() }
        ).flow
            .map { pagingData ->
                pagingData.map { it.characterEntityToDomain() }
            }
    }

//    override fun getAllTailBeast() = flow {
//        val response = narutoApi.getAllTailBeast()
//        emit(response.tailedBeasts.toModelList())
////        val entities = response.tailedBeasts.toEntityList()
////        tailBeastDao.addBeast(entities)
////        emit(tailBeastDao.getTailBeast().map { it.tailBeastEntityToDomain() })
//    }

}