package com.jeric.ricafrente.naruto.core.network.model.akatsuki

import kotlinx.serialization.Serializable

@Serializable
data class AkatsukiDto(
    val id: Int,
    val family: AkatsukiFamilyDto?,
    val images: List<String>? =null,
    val jutsu: List<String>?= null,
    val name: String,
    val natureType: List<String>?= null,
    val personal: AkatsukiPersonalDto?=null,
    val tools: List<String>?=null,
)

@Serializable
data class AkatsukiFamilyDto(
    val father: String? = null,
    val mother: String? = null,
    val brother: String? = null,
    val daughter: String? = null,
    val wife: String? = null
)

@Serializable
data class AkatsukiPersonalDto(
    val birthdate: String? = null,
    val bloodType: String? = null,
    val sex: String? = null
)