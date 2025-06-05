package com.jeric.ricafrente.naruto.core.utils

fun List<String>?.toJoinedString(separator: String = ","): String? {
    return this?.joinToString(separator)
}

fun List<Int>?.toJoinedInt(separator: String = ","): String {
    return this?.joinToString(separator) ?: ""
}

fun String?.convertStringToList(separator: String = ","): List<String> {
    return this?.split(separator) ?: emptyList()
}

fun String.toIntList(separator: String = ","): List<Int> {
    return this.split(separator)
        .mapNotNull { it.toIntOrNull() }
}
