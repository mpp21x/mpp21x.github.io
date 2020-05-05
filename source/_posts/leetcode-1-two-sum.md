---
title: LeetCode 訓練第一篇(1) - Two Sum
date: 2020-05-06 01:18:47
tags:
- leetcode
- javascript
- algorithm
img: /medias/page_images/200505_best.png
---
# LeetCode 訓練第一篇(1) - Two Sum

* [Two Sum 題目連結](https://leetcode.com/problems/two-sum/)
* 難度：`easy`
* 程式語言：`javascript`

## 題目講解

### 原文

Given an array of integers, return indices of the two numbers such that they add up to a specific target.

You may assume that each input would have exactly one solution, and you may not use the same element twice.

Example:

```
Given nums = [2, 7, 11, 15], target = 9,

Because nums[0] + nums[1] = 2 + 7 = 9,
return [0, 1].
```

### 中文

給予一個充滿整數的陣列 `nums` 以及一個指定的整數 `target`，我們必須找出在這個陣列中的某兩個數，此數總和必然是 **指定的整數**，並將其索引值放在一個陣列之後回傳出去。

在每個陣列中，必然只有兩個數的總和跟  **指定的整數** 一樣，同時這兩個數必然是不同的數，不能為同一個數。

舉例來說：

`nums` 為 `[2, 7, 11, 15]`，而 `target` 為 `9`，因為 `nums[0]` 以及 `nums[1]` 總和為 `9` 跟 `target` 一樣，所以答案就是 `[0, 1]`。


## 題目解析思路

首先我們要知道，答案的兩個數總和（假設叫做 `a` 和 `b`，`a` 在 `b` 的前面！），跟 `target` 是一樣的，透過簡單的推理可得知：

`簡單的數學邏輯推理`：target = a + b，traget - a = b 或是 target - b = a。 

所以我們可以透過 `target` 減少 `a` 的結果，再搭配另一個數字來確認總和是否為 `target`，是的話就代表這個數字就是我們要找到的 `b`。

由於要得到答案，流程必然經過這三個步驟，讓我們先給這三個步驟一個代稱，以便後續稱呼：

* `遍歷步驟`：也就是一一取出 nums 的一個數字
* `取得待確認值步驟`：取出 nums 的一個數字後扣掉 `target` 取得待確認值，只要這個待確認值存在於 `nums` 其中的另一個位置（不能是同一個位置），則此數則為 `a` 或 `b`。
* `找出總和步驟`： 再來就是找出這個是這一題比較重要的環節，因為這個環節按照上面 `簡單的數學邏輯推理` 我們可以知道有兩種思路：
    * `用加的來找出總和步驟`
    * `用減的來找出總和步驟`

### 用加的來找出總和步驟實作
`用加的來找出總和步驟` ，最直覺的作法便是遍歷 `nums`，每個數字取出來扣掉 `target` 取得運算後的待確認值之後，在遍歷一次整個 `nums` 慢慢相加找出答案，但這樣子的做法，時間複雜度會變成 `O(n²)` 了。

那麼有沒有更好的做法？有，也就是 `用減的來找出總和步驟`

### 用減的來找出總和步驟實作

在每一次遍歷運算的時候，把待確認值放在一個額外新增的一個變數 `map`，將待確認值作為 `key`，Index 作為 `value` ，這樣只要每次運算完之後，都會將運算結果儲存到這個 `map` 裡面。

這樣做的好處是時間複雜度會變成 `O(n)`了。

當今天遍歷到答案 `a` 的時候，因為 `map` 裡面還沒有放到 `b`，所以 `a` 先被儲存至 `b` ，然後接下來一一遍歷直到 `b` 出現的時候，因為此時 `a` 已經存在於 `map` 了，所以直接使用 `map[taget - a]` 判斷是否存在，只要存在就幾乎找出答案了。

不過答案要的是索引值所以不要忘了把回傳 `a` 和 `b` 在 `nums` 的索引值，而非其值。

### 實作程式碼

將上述過程轉化程式碼便是如下：

```javascript
var twoSum = function (nums, target) {
    let map = {};
    for (let i = 0; i < nums.length; i++) {
        if (map[target - nums[i]] > -1) {
            return [map[target - nums[i]], i];
        }
        map[nums[i]] = i;
    }
};

// 驗算
// console.log(twoSum([2, 7, 11, 15], 9));
```

#### 其他

如果陣列數字無限大，可考慮將 `nums.length` 取出作為一個變數，詳細可參考連結 ：[For-loop performance: storing array length in a variable
](https://stackoverflow.com/questions/17989270/for-loop-performance-storing-array-length-in-a-variable)。

此次練習被收錄在我的 GitHub [點我連結](https://github.com/mpp21x/algorithm-exercise/tree/master/1.twoSum)。 





