package com.jeric.narutoapp.data.api

import com.jeric.narutoapp.data.model.NarutoCharacter
import retrofit2.http.GET
import retrofit2.http.Query

interface NarutoApi {
    @GET("characters")
    suspend fun getAllCharacters(
        @Query("page") page: Int = 1,
        @Query("limit") limit: Int = 20
    ): NarutoCharacter
} 