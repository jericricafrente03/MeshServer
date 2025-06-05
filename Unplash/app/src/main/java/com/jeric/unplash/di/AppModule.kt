package com.jeric.unplash.di

import android.content.Context
import androidx.room.Room
import com.jakewharton.retrofit2.converter.kotlinx.serialization.asConverterFactory
import com.jeric.unplash.data.local.UnSplashDatabase
import com.jeric.unplash.data.remote.dto.UnSplashServiceApi
import com.jeric.unplash.data.repository.UnsplashRepositoryImpl
import com.jeric.unplash.data.util.Constants
import com.jeric.unplash.data.util.Constants.IMAGE_VISTA_DATABASE
import com.jeric.unplash.domain.repository.UnsplashRepository
import dagger.Module
import dagger.Provides
import dagger.hilt.InstallIn
import dagger.hilt.android.qualifiers.ApplicationContext
import dagger.hilt.components.SingletonComponent
import kotlinx.serialization.json.Json
import okhttp3.MediaType.Companion.toMediaType
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Retrofit
import java.util.concurrent.TimeUnit
import javax.inject.Singleton

@Module
@InstallIn(SingletonComponent::class)
object AppModule {
    @Provides
    @Singleton
    fun provideUnsplashApiService(okHttpClient: OkHttpClient): UnSplashServiceApi {
        val contentType = "application/json".toMediaType()
        val json = Json { ignoreUnknownKeys = true }
        val retrofit = Retrofit.Builder()
            .addConverterFactory(json.asConverterFactory(contentType))
            .baseUrl(Constants.BASE_URL)
            .client(okHttpClient)
            .build()
        return retrofit.create(UnSplashServiceApi::class.java)
    }

    @Singleton
    @Provides
    fun provideOkHttpClient(loggingInterceptor: HttpLoggingInterceptor): OkHttpClient {
        return OkHttpClient.Builder()
            .callTimeout(40, TimeUnit.SECONDS)
            .connectTimeout(40, TimeUnit.SECONDS)
            .readTimeout(40, TimeUnit.SECONDS)
            .writeTimeout(40, TimeUnit.SECONDS)
            .addInterceptor(loggingInterceptor)
            .build()
    }

    @Singleton
    @Provides
    fun provideLoggingInterceptor(): HttpLoggingInterceptor {
        return HttpLoggingInterceptor().apply {
            level = HttpLoggingInterceptor.Level.BODY
        }
    }

    @Provides
    @Singleton
    fun provideUnSplashDatabase(
        @ApplicationContext context: Context
    ): UnSplashDatabase {
        return Room.databaseBuilder(
                context,
                UnSplashDatabase::class.java,
                IMAGE_VISTA_DATABASE
            )
            .build()
    }

    @Provides
    @Singleton
    fun provideImageRepository(
        apiService: UnSplashServiceApi,
        database: UnSplashDatabase
    ): UnsplashRepository {
        return UnsplashRepositoryImpl(apiService, database)
    }


}