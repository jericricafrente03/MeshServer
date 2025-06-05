package com.jeric.ricafrente.naruto.core.network.model.character

import kotlinx.serialization.Serializable

@Serializable
data class CharacterDto(
    val id: Int,
    val name: String,
    val images: List<String>?,
    val family: FamilyDto? = null,
    val jutsu: List<String>?,
    val natureType: List<String>? = null,
    val personal: PersonalDto?,
    val tools: List<String>? = null,
)

@Serializable
data class FamilyDto(
    val father: String? = null,
    val mother: String? = null,
    val brother: String? = null,
    val daughter: String? = null,
    val wife: String? = null
)
@Serializable
data class PersonalDto(
    val birthdate: String? = null,
    val bloodType: String? = null,
    val sex: String? = null
)