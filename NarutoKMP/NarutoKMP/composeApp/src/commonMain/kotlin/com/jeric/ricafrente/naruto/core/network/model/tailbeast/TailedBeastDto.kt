package com.jeric.ricafrente.naruto.core.network.model.tailbeast

import kotlinx.serialization.Serializable

@Serializable
data class TailedBeastDto(
    val id: Int,
    val images: List<String>? = null,
    val jutsu: List<String>? = null,
    val name: String,
    val natureType: List<String>? = null,
    val tools: List<String>? = null,
    val uniqueTraits: List<String>? = null,
)
