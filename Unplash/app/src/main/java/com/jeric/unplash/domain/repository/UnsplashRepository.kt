package com.jeric.unplash.domain.repository

import androidx.paging.PagingData
import com.jeric.unplash.domain.model.UnsplashImage
import kotlinx.coroutines.flow.Flow

interface UnsplashRepository {
    fun getEditorialFeedImages(): Flow<PagingData<UnsplashImage>>
    fun getFavoriteImageIds(): Flow<List<String>>
//    fun getEditorialFeedImagesFlow(): Flow<List<UnsplashImage>>
}