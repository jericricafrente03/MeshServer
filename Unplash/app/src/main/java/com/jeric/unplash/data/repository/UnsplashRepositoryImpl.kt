package com.jeric.unplash.data.repository

import android.util.Log
import androidx.paging.ExperimentalPagingApi
import androidx.paging.Pager
import androidx.paging.PagingConfig
import androidx.paging.PagingData
import androidx.paging.map
import com.jeric.unplash.data.local.UnSplashDatabase
import com.jeric.unplash.data.mappers.toDomainModel
import com.jeric.unplash.data.mappers.toDomainModelList
import com.jeric.unplash.data.mappers.toEntity
import com.jeric.unplash.data.mappers.toEntityList
import com.jeric.unplash.data.paging.SplashRemoteMediator
import com.jeric.unplash.data.remote.dto.UnSplashServiceApi
import com.jeric.unplash.data.util.Constants.ITEMS_PER_PAGE
import com.jeric.unplash.domain.model.UnsplashImage
import com.jeric.unplash.domain.repository.UnsplashRepository
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.flow
import kotlinx.coroutines.flow.map

@OptIn(ExperimentalPagingApi::class)
class UnsplashRepositoryImpl(
    private val unsplashApi: UnSplashServiceApi,
    private val database: UnSplashDatabase
) : UnsplashRepository {

    private val editorialFeedDao = database.editorialFeedDao()

    override fun getEditorialFeedImages(): Flow<PagingData<UnsplashImage>> {
        return Pager(
            config = PagingConfig(pageSize = ITEMS_PER_PAGE),
            remoteMediator = SplashRemoteMediator(unsplashApi, database),
            pagingSourceFactory = { editorialFeedDao.getAllEditorialFeedImages() }
        )
            .flow
            .map { pagingData ->
                pagingData.map { it.toDomainModel() }
            }
    }

    override fun getFavoriteImageIds() = flow {
        emit(listOf(""))
    }

//    override fun getEditorialFeedImagesFlow() = flow {
//        val result = unsplashApi.getEditorialFeedImages(page = 1, perPage = 10)
//        editorialFeedDao.insertEditorialFeedImages(result.toEntityList())
//        emit(editorialFeedDao.getAllEditorialFeedImages().map { it.toDomainModel() })
//    }

}