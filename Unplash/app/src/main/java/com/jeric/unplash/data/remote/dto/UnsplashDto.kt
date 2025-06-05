package com.jeric.unplash.data.remote.dto

import com.google.gson.annotations.SerializedName
import kotlinx.serialization.Serializable


@Serializable
data class UnsplashDto(
    val description: String,
    val height: Int,
    val id: String,
    val urls: UrlsDto,
    val user: UserDto,
    val width: Int
)
@Serializable
data class UrlsDto(
    val full: String,
    val raw: String,
    val regular: String,
    val small: String,
    val thumb: String
)

@Serializable
data class UserDto(
    val links: UserLinksDto,
    val name: String,
    @SerializedName("profile_image")
    val profileImage: ProfileImageDto,
    val username: String
)

@Serializable
data class ProfileImageDto(
    val small: String
)
@Serializable
data class UserLinksDto(
    val html: String,
    val likes: String,
    val photos: String,
    val portfolio: String,
    val self: String
)
