package com.jeric.ricafrente.naruto.core.network.model.character

import kotlinx.serialization.Serializable

@Serializable
data class CharactersResponse(
   val characters: List<CharacterDto>,
   val currentPage: Int,
   val pageSize: Int,
   val total: Int
)