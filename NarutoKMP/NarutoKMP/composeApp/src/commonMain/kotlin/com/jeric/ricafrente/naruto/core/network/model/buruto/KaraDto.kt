package com.jeric.ricafrente.naruto.core.network.model.buruto

import kotlinx.serialization.Serializable

@Serializable
data class KaraDto(
    val family: KaraFamilyDto?,
    val id: Int,
    val images: List<String>?= null,
    val jutsu: List<String>?= null,
    val name: String,
    val natureType: List<String>?=null,
    val personal: KaraPersonalDto?,
    val tools: List<String>?=null,
)

@Serializable
data class KaraFamilyDto(
    val father: String? = null,
    val mother: String? = null,
    val brother: String? = null,
    val daughter: String? = null,
    val wife: String? = null
)

@Serializable
data class KaraPersonalDto(
    val birthdate: String? = null,
    val bloodType: String? = null,
    val sex: String? = null
)