package com.jeric.ricafrente.naruto.core.model.tailedbeast

data class TailedBeastModel(
    val id: Int,
    val name: String,
    val images: List<String>?,
    val jutsu: List<String>?,
    val natureType: List<String>?,
    val tools: List<String>?,
    val uniqueTraits: List<String>?,
)