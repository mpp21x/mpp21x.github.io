---
title: LeetCode 訓練(2) -  Kids With the Greatest Number of Candies
date: 2020-05-06 09:46:42
tags: 
- leetcode
- javascript
- algorithm
img: /medias/page_images/leetcode-1282-group-the-People-Given-the-Group-Size-They-Belong-To.jpeg
---
* link：[Kids With the Greatest Number of Candies](https://leetcode.com/problems/kids-with-the-greatest-number-of)
* difficulty：`easy`
* language：`javascript`

## 題目講解

### 原文

Given the array candies and the integer extraCandies, where candies[i] represents the number of candies that the ith kid has.

For each kid check if there is a way to distribute extraCandies among the kids such that he or she can have the greatest number of candies among them. Notice that multiple kids can have the greatest number of candies.

Example 1:

```
Input: candies = [2,3,5,1,3], extraCandies = 3
Output: [true,true,true,false,true] 
Explanation: 
Kid 1 has 2 candies and if he or she receives all extra candies (3) will have 5 candies --- the greatest number of candies among the kids. 
Kid 2 has 3 candies and if he or she receives at least 2 extra candies will have the greatest number of candies among the kids. 
Kid 3 has 5 candies and this is already the greatest number of candies among the kids. 
Kid 4 has 1 candy and even if he or she receives all extra candies will only have 4 candies. 
Kid 5 has 3 candies and if he or she receives at least 2 extra candies will have the greatest number of candies among the kids. 
```

Example 2:

```
Input: candies = [4,2,1,1,2], extraCandies = 1
Output: [true,false,false,false,false] 
Explanation: There is only 1 extra candy, therefore only kid 1 will have the greatest number of candies among the kids regardless of who takes the extra candy.
```

Example 3:

```
Input: candies = [12,1,12], extraCandies = 10
Output: [true,false,true]
```

Constraints:

* `2 <= candies.length <= 100`
* `1 <= candies[i] <= 100`
* `1 <= extraCandies <= 50`

### 題目解釋

給予一個充滿整數的陣列 `candies` 以及一個指定的整數 `extraCandies`，我們需要取出 `candies` 中的每一個整數，再加上 `extraCandies` 之後，判斷其是否大於或等於  `candies` 中最大的那個數字，若是則為 `true` 否則為 `false`。

再將每一個整數的運算結果放在一個陣列後回傳，也就是說必須回傳一個與 `candies` 同等長度以及皆為 boolean 值的陣列。

## 題目解析思路

這題大致可以幾個步驟（數字為代稱，不一定為實際順序）：

1. **遍歷取出數字**：將 `candies` 一一取出。這一步驟讓時間複雜度至少會是 `O(n)`。
2. **運算及比較**： 加上 `extraCandies` 之後進行比較。
3. **找出最大值**： 找出 `candies` 最大的數值。

這裡的 **步驟 1** 和 **步驟 2** ，在邏輯上必然有先後關聯的，可將步驟 1 和 步驟 2 可視為一體，而接下來則是思考 要先 **步驟 3** 或是 **步驟 1 & 2**：

若先執行 **步驟 1 & 2** -> **步驟 3**，這樣一邊遍歷取出元素 又得重複尋找出最大值，時間複雜度為 `O(n²)`

如果先 **步驟 3** -> **步驟 1 & 2**，一開始就找出最大數，時間複雜度為 `2n` 而已。

## 實作程式碼

將上述過程轉化程式碼便是如下：

```javascript
var kidsWithCandies = function (candies, extraCandies) {
    // 3. 找出最大值
    const maxNum = candies.reduce((accumulator, currentValue) => {
        return currentValue > accumulator ? currentValue : accumulator;
    }, candies[0]);

    const answer = [];

    const length = candies.length;
    // 1. 遍歷取出數字 以及 2. 運算及比較
    for (let i = 0; i < length; i++) {
        answer.push((candies[i] + extraCandies) >= maxNum);
    }

    return answer;
};

```

## 其他

### GitHub 紀錄

此次練習被收錄在我的 GitHub [點我連結](https://github.com/mpp21x/algorithm-exercise/tree/master/1431.Kids_With_the_Greatest_Number_of_Candies)。 
