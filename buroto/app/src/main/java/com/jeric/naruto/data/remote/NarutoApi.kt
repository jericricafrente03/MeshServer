package com.jeric.naruto.data.remote

import com.jeric.naruto.data.remote.dto.character.CharactersResponse
import com.jeric.naruto.data.remote.dto.tailbeast.TailBeastResponse
import com.jeric.naruto.util.Constants
import retrofit2.http.GET
import retrofit2.http.Query

interface NarutoApi {

    @GET("characters")
    suspend fun getAllCharacters(
        @Query("page") page: Int = 1,
        @Query("limit") limit: Int = Constants.SIZE
    ): CharactersResponse

    @GET("tailed-beasts")
    suspend fun getAllTailBeast(): TailBeastResponse

}