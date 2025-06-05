package com.jeric.naruto.data.remote.dto.character

import com.google.gson.annotations.SerializedName

data class CharactersResponse(
    @SerializedName("characters") val characters: List<CharacterDto>,
    @SerializedName("currentPage") val currentPage: Int,
    @SerializedName("pageSize") val pageSize: Int,
    @SerializedName("total") val total: Int
)